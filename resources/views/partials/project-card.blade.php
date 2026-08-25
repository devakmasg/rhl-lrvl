{{-- Expects $project: App\Models\Project --}}
<article class="pcard" data-status="{{ strtolower($project->status) }}" data-type="{{ strtolower($project->type) }}" data-location="{{ strtolower($project->location) }}" data-slug="{{ $project->slug }}">
  <div class="pcard-media"><img src="{{ $project->hero_image_url }}" alt="{{ $project->name }}" loading="lazy"></div>
  <span class="pcard-status is-{{ strtolower($project->status) }}">{{ $project->status }}</span>
  <div class="pcard-body">
    <span class="pcard-meta">{{ $project->type }} &middot; {{ $project->location }}</span>
    <h3><a class="pcard-link" href="{{ route('projects.show', $project->slug) }}">{{ $project->name }}</a></h3>
    <p>{{ $project->summary }}</p>
  </div>
</article>
