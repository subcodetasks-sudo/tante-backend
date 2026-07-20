<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::query()
            ->latest()
            ->get()
            ->map(fn (Testimonial $testimonial) => [
                'id' => $testimonial->id,
                'name' => $testimonial->name,
                'rating' => $testimonial->rating,
                'review' => $testimonial->review,
                'image' => $testimonial->image_url,
            ]);

        return response()->json([
            'data' => $testimonials,
        ]);
    }
}
