<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Eductaion;
use App\Models\Experience;
use App\Models\HomePage;
use App\Models\PersonalInformation;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Test;

class FrontApi extends Controller
{
    public function getHomePage()
    {
        $home_page = HomePage::first();
        return response()->json($home_page);
    }
    public function getSkills()
    {
        $skills = Skill::all()->groupBy('title');
        return response()->json($skills);
    }
    public function getServices()
    {
        $services = Service::all();
        return response()->json($services);
    }
    public function getPersonalInformations()
    {
        $personal_informations = PersonalInformation::all();
        return response()->json($personal_informations);
    }
    public function getEducations()
    {
        $educations = Eductaion::all();
        return response()->json($educations);
    }
    public function getExperiences()
    {
        $experiences = Experience::all();
        return response()->json($experiences);
    }
    public function getTestimonials()
    {
        $testimonials = Testimonial::all();
        return TestimonialResource::collection($testimonials);
    }
    public function getProjects()
    {
        $projects = Project::with('tags')->get();
        return ProjectResource::collection($projects);
    }

    public function getProjectTags()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }
}
