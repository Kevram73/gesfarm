<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VeterinaryTreatment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VeterinaryController extends Controller
{
    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer tous les traitements vétérinaires
     */
    public function getTreatments(Request $request): JsonResponse
    {
        $query = VeterinaryTreatment::with('user')
            ->orderBy('treatment_date', 'desc');

        // Filtres
        if ($request->has('treatment_type')) {
            $query->where('treatment_type', $request->treatment_type);
        }
        if ($request->has('animal_type')) {
            $query->where('animal_type', $request->animal_type);
        }
        if ($request->has('animal_id')) {
            $query->where('animal_id', $request->animal_id);
        }
        if ($request->has('date_from')) {
            $query->where('treatment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('treatment_date', '<=', $request->date_to);
        }

        $treatments = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $treatments
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Créer un nouveau traitement vétérinaire
     */
    public function createTreatment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'treatment_type' => 'required|string|max:255',
            'treatment_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'treatment_date' => 'required|date',
            'treatment_time' => 'nullable|date_format:H:i',
            'animal_type' => 'required|string|max:255',
            'animal_id' => 'nullable|integer',
            'animal_identifier' => 'nullable|string|max:255',
            'veterinarian_name' => 'nullable|string|max:255',
            'veterinarian_license' => 'nullable|string|max:255',
            'medications' => 'nullable|array',
            'dosages' => 'nullable|array',
            'cost' => 'nullable|numeric|min:0',
            'next_treatment_date' => 'nullable|date|after:treatment_date',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $treatment = VeterinaryTreatment::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Traitement vétérinaire créé avec succès',
            'data' => $treatment->load('user')
        ], 201);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer un traitement spécifique
     */
    public function getTreatment(VeterinaryTreatment $treatment): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $treatment->load('user')
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Mettre à jour un traitement
     */
    public function updateTreatment(Request $request, VeterinaryTreatment $treatment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'treatment_type' => 'sometimes|string|max:255',
            'treatment_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'treatment_date' => 'sometimes|date',
            'treatment_time' => 'nullable|date_format:H:i',
            'animal_type' => 'sometimes|string|max:255',
            'animal_id' => 'nullable|integer',
            'animal_identifier' => 'nullable|string|max:255',
            'veterinarian_name' => 'nullable|string|max:255',
            'veterinarian_license' => 'nullable|string|max:255',
            'medications' => 'nullable|array',
            'dosages' => 'nullable|array',
            'cost' => 'nullable|numeric|min:0',
            'next_treatment_date' => 'nullable|date|after:treatment_date',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $treatment->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Traitement vétérinaire mis à jour avec succès',
            'data' => $treatment->load('user')
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Supprimer un traitement
     */
    public function deleteTreatment(VeterinaryTreatment $treatment): JsonResponse
    {
        $treatment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Traitement vétérinaire supprimé avec succès'
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer le planning des soins
     */
    public function getSchedule(Request $request): JsonResponse
    {
        $query = VeterinaryTreatment::with('user')
            ->whereNotNull('next_treatment_date')
            ->where('next_treatment_date', '>=', now())
            ->orderBy('next_treatment_date');

        if ($request->has('animal_type')) {
            $query->where('animal_type', $request->animal_type);
        }
        if ($request->has('days_ahead')) {
            $days = $request->days_ahead ?? 30;
            $query->where('next_treatment_date', '<=', now()->addDays($days));
        }

        $schedule = $query->get();

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer les rappels de soins
     */
    public function getReminders(): JsonResponse
    {
        $reminders = VeterinaryTreatment::with('user')
            ->whereNotNull('next_treatment_date')
            ->where('next_treatment_date', '<=', now()->addDays(7))
            ->where('next_treatment_date', '>=', now())
            ->orderBy('next_treatment_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reminders,
            'count' => $reminders->count()
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer l'historique médical d'un animal
     */
    public function getAnimalHistory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|integer',
            'animal_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $history = VeterinaryTreatment::with('user')
            ->where('animal_id', $request->animal_id)
            ->where('animal_type', $request->animal_type)
            ->orderBy('treatment_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * @group Gestion Vétérinaire
     * 
     * Récupérer les statistiques vétérinaires
     */
    public function getVeterinaryStats(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->subMonths(6));
        $endDate = $request->get('end_date', now());

        $stats = [
            'total_treatments' => VeterinaryTreatment::whereBetween('treatment_date', [$startDate, $endDate])->count(),
            'treatments_by_type' => VeterinaryTreatment::whereBetween('treatment_date', [$startDate, $endDate])
                ->selectRaw('treatment_type, COUNT(*) as count')
                ->groupBy('treatment_type')
                ->get(),
            'treatments_by_animal_type' => VeterinaryTreatment::whereBetween('treatment_date', [$startDate, $endDate])
                ->selectRaw('animal_type, COUNT(*) as count')
                ->groupBy('animal_type')
                ->get(),
            'total_cost' => VeterinaryTreatment::whereBetween('treatment_date', [$startDate, $endDate])
                ->sum('cost'),
            'upcoming_treatments' => VeterinaryTreatment::where('next_treatment_date', '>=', now())
                ->where('next_treatment_date', '<=', now()->addDays(30))
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
