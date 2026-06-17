@extends('templates.layouts.minimalist_layout')
@section('title', env('APP_TITLE'))

@php
    $skillCatalog = collect($skillsJson['languages'] ?? [])
        ->merge($skillsJson['frameworks'] ?? [])
        ->merge($skillsJson['software_skills'] ?? []);

    $skillMap = $skillCatalog
        ->mapWithKeys(fn($item, $code) => [$code => $item['name'] ?? $code])
        ->all();

    $skillIconMap = $skillCatalog
        ->mapWithKeys(fn($item, $code) => [$code => $item['icon'] ?? 'fa-solid fa-code'])
        ->all();

    $projectTags = function ($project) use ($skillMap) {
        $skillCodes = json_decode($project->skills ?? '[]', true) ?: [];
        return collect($skillCodes)->map(fn($code) => $skillMap[$code] ?? $code)->values();
    };

    $projectImage = function ($project) {
        $images = json_decode($project->images ?? '[]', true) ?: [];
        if (count($images) === 0) {
            return 'https://via.placeholder.com/800x600?text=Project';
        }

        return str_starts_with($images[0], 'http') ? $images[0] : '/' . ltrim($images[0], '/');
    };

    $filters = $projects
        ->flatMap(fn($project) => $projectTags($project))
        ->unique()
        ->take(8)
        ->values();

    $totalProjects = max($projects->count(), 1);
    $projectSkillUsage = $projects
        ->flatMap(fn($project) => json_decode($project->skills ?? '[]', true) ?: [])
        ->countBy();
@endphp

@section('main')
    <section id="home" class="hero">
        <canvas id="hero-canvas"></canvas>
        <div class="container">
            <div class="hero-content">
                <h1 class="fade-in">
                    {{ __('site.other.welcome') }} <span class="text-primary">{{ $infos['name'] }}</span>
                </h1>
                <h2 class="fade-in">{{ $infos['position'] }}</h2>

                <div class="social-links fade-in">
                    @if (!is_null($contacts['instagram']))
                        <a href="https://instagram.com/{{ $contacts['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fa fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if (!is_null($contacts['github']))
                        <a href="https://github.com/{{ $contacts['github'] }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                    @if (!is_null($contacts['linkedin']))
                        <a href="https://linkedin.com/in/{{ $contacts['linkedin'] }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                    @if (!is_null($contacts['twitter']))
                        <a href="https://x.com/{{ $contacts['twitter'] }}" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                </div>

                <a href="#projects" class="btn btn-primary fade-in">
                    {{ __('site.other.button') }} 
                    <span style="margin-left: 10px">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="about section-bg">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('site.head.1') }}</h2>
                <div class="section-divider"></div>
            </div>

            <div class="about-content">
                <div class="about-image">
                    <div class="image-container">
                        <img src="{{ $customization('myphoto', '/img/my.jpg') }}" alt="{{ $infos['fullname'] }}">
                    </div>
                    <div class="bg-accent bottom-right"></div>
                    <div class="bg-accent top-left"></div>
                </div>

                <div class="about-text">
                    <h3>
                        <span class="text-primary">{{ $infos['fullname'] }}</span>, {{ $infos['position'] }}
                    </h3>
                    <p>{{ $infos['description'] }}</p>

                    @if (!is_null($contacts['csv'] ?? null))
                        <a href="{{ $contacts['csv'] }}" class="btn btn-primary about-cv-btn" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-file-alt"></i> {{ __('site.other.cv') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="projects">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('site.titles.2') }}</h2>
                <div class="section-divider"></div>
                <p>{{ __('site.minimalist.projects_subtitle') }}</p>
            </div>

            @if ($filters->count() > 0)
                <div class="project-filters">
                    <button class="filter-btn active" data-filter="all">{{ __('site.minimalist.all') }}</button>
                    @foreach ($filters as $filter)
                        <button class="filter-btn" data-filter="{{ $filter }}">{{ $filter }}</button>
                    @endforeach
                </div>
            @endif

            <div class="projects-grid">
                @if ($projects->count() > 0)
                    @foreach ($projects as $project)
                    @php
                        $tags = $projectTags($project);
                    @endphp
                    <div class="project-card" data-tags="{{ $tags->implode(',') }}">
                        <div class="project-image">
                            <img src="{{ $projectImage($project) }}" alt="{{ $project->localizedName() }}">
                        </div>
                        <div class="project-content">
                            <h3>{{ $project->localizedName() }}</h3>
                            <p>{{ $project->localizedPreview() }}</p>
                            <div class="project-tags">
                                @foreach ($tags as $tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                            <button class="btn btn-outline view-project" type="button">{{ __('site.minimalist.view_details') }}</button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>{{ __('site.minimalist.no_projects') }}</p>
                @endif
            </div>
        </div>

        <div class="modal" id="project-modal" aria-hidden="true">
            <div class="modal-content">
                <button class="modal-close" type="button" aria-label="{{ __('site.minimalist.close') }}"><i class="fas fa-times"></i></button>
                <h2 class="modal-title"></h2>
                <div class="modal-tags"></div>
                <div class="modal-media">
                    <div class="modal-image">
                        <img src="" alt="">
                    </div>
                    <div class="modal-gallery"></div>
                    <div class="modal-video"></div>
                </div>
                <div class="modal-description"></div>
                <div class="modal-links"></div>
            </div>
        </div>
    </section>

    <section id="skills" class="skills section-bg">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('site.titles.3') }}</h2>
                <div class="section-divider"></div>
                <p>{{ __('site.minimalist.skills_subtitle') }}</p>
            </div>

            <div class="skills-grid">
                @if ($skills->count() > 0)
                    @foreach ($skills as $item)
                        @php
                            $projectUsageCount = (int) ($projectSkillUsage[$item->code] ?? 0);
                            $progress = min((int) round(($projectUsageCount / $totalProjects) * 100), 100);
                            $skillName = $skillMap[$item->code] ?? $item->code;
                            $skillIcon = $skillIconMap[$item->code] ?? 'fa-solid fa-code';
                            $skillYears = $item->year ? max(now()->year - (int) $item->year + 1, 0) : null;
                        @endphp
                        <article class="skill-card">
                            <div class="skill-card-top">
                                <span class="skill-icon"><i class="{{ $skillIcon }}"></i></span>
                                <span class="skill-years">
                                    @if ($skillYears)
                                        {{ trans_choice('site.minimalist.skill_years', $skillYears, ['count' => $skillYears]) }}
                                    @else
                                        {{ trans_choice('site.minimalist.projects_count', $projectUsageCount, ['count' => $projectUsageCount]) }}
                                    @endif
                                </span>
                            </div>
                            <h3>{{ $skillName }}</h3>
                            <div class="skill-card-footer">
                                <div class="skill-bar" aria-hidden="true">
                                    <div class="skill-progress" style="width: {{ $progress }}%"></div>
                                </div>
                                <span>{{ trans_choice('site.minimalist.projects_count', $projectUsageCount, ['count' => $projectUsageCount]) }}</span>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p>{{ __('site.minimalist.no_skills') }}</p>
                @endif
            </div>
        </div>
    </section>

    @if (isset($experiences) && $experiences->count() > 0)
        <section id="experience" class="experience">
            <div class="container">
                <div class="section-header">
                    <h2>{{ __('site.minimalist.experience_title') }}</h2>
                    <div class="section-divider"></div>
                    <p>{{ __('site.minimalist.experience_subtitle') }}</p>
                </div>

                <div class="experience-timeline">
                    @foreach ($experiences as $experience)
                        @php
                            $experienceDescription = $experience->localizedDescription();
                            $experiencePromotions = $experience->localizedPromotions();
                            $experienceSkills = collect($experience->skills ?? [])
                                ->map(fn($code) => [
                                    'name' => $skillMap[$code] ?? $code,
                                    'icon' => $skillIconMap[$code] ?? 'fa-solid fa-code',
                                ])
                                ->values();
                        @endphp
                        <div class="experience-item">
                            <div class="experience-card">
                                <div class="experience-header">
                                    <div>
                                        <h3 class="experience-position">{{ $experience->localizedPosition() }}</h3>
                                        <h4 class="experience-company">{{ $experience->company }}</h4>
                                    </div>
                                    <div class="experience-meta">
                                        <div class="experience-duration">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>
                                                {{ $experience->start_date->format('M Y') }}
                                                -
                                                {{ $experience->current || !$experience->end_date ? __('site.minimalist.present') : $experience->end_date->format('M Y') }}
                                            </span>
                                        </div>
                                        @if ($experience->location)
                                            <div class="experience-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $experience->location }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($experienceDescription)
                                    <div class="experience-description">
                                        <ul>
                                            @foreach (preg_split('/\r\n|\r|\n/', $experienceDescription) as $line)
                                                @if (trim($line) !== '')
                                                    <li>{{ $line }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (count($experiencePromotions) > 0)
                                    <div class="experience-promotions">
                                        <h5>{{ __('site.minimalist.promotions') }}</h5>
                                        <ul>
                                            @foreach ($experiencePromotions as $promotion)
                                                <li>{{ $promotion }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($experienceSkills->count() > 0)
                                    <div class="experience-skills">
                                        @foreach ($experienceSkills as $skill)
                                            <span class="experience-skill">
                                                <i class="{{ $skill['icon'] }}"></i>
                                                {{ $skill['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="contact" class="contact section-bg">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('site.minimalist.contact_title') }}</h2>
                <div class="section-divider"></div>
                <p>{{ __('site.minimalist.contact_subtitle') }}</p>
            </div>

            <div class="contact-container">
                <div class="contact-info">
                    @if (!is_null($contacts['email_business'] ?? null))
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <h3>{{ __('site.other.email_business') }}</h3>
                                <p>{{ $contacts['email_business'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if (!is_null($contacts['email_personal'] ?? null))
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-envelope-open-text"></i></div>
                            <div>
                                <h3>{{ __('site.other.email_personal') }}</h3>
                                <p>{{ $contacts['email_personal'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if (!is_null($contacts['tel'] ?? null))
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <h3>{{ __('site.minimalist.phone') }}</h3>
                                <p>{{ $contacts['tel'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if (!is_null($contacts['linkedin'] ?? null))
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fab fa-linkedin"></i></div>
                            <div>
                                <h3>LinkedIn</h3>
                                <p>linkedin.com/in/{{ $contacts['linkedin'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="contact-form-container">
                    @if (session('contact_success'))
                        <p class="form-success">
                            <i class="fas fa-circle-check"></i>
                            {{ session('contact_success') }}
                        </p>
                    @endif

                    @if ($errors->any())
                        <div class="form-error-summary" role="alert">
                            <strong>{{ __('site.minimalist.contact_error_title') }}</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="contact-form" class="contact-form" action="{{ route('contact.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">{{ __('site.minimalist.name') }}</label>
                                <input class="@error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('site.minimalist.name_placeholder') }}" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="@error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required>
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">{{ __('site.minimalist.subject') }}</label>
                            <input class="@error('subject') is-invalid @enderror" type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="{{ __('site.minimalist.subject_placeholder') }}" required>
                            @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="message">{{ __('site.minimalist.message') }}</label>
                            <textarea class="@error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="{{ __('site.minimalist.message_placeholder') }}" required>{{ old('message') }}</textarea>
                            @error('message') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane"></i> {{ __('site.minimalist.send_message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <button type="button" class="back-to-top" aria-label="{{ __('site.minimalist.back_to_top') }}">
        <i class="fas fa-arrow-up"></i>
    </button>
@endsection
