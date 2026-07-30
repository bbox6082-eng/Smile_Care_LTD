@extends('layouts.dashboard')

@section('content')

@php

$totalCategories = count($sections);

$totalReports = collect($sections)->sum(function($section){
    return count($section['reports']);
});

@endphp


<div class="container-fluid">

    {{-- ================= HEADER ================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header report-header">

            <div class="d-flex align-items-center">
                <div class="ms-3">

                    <h2 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Reports Dashboard
                    </h2>
                    <p class="mb-0">

                        View, Filter, Export and Print all reports from one place.

                    </p>

                </div>

            </div>

            <div class="mt-4">

                <div class="search-wrapper">

                    <i class="fas fa-search search-icon"></i>

                    <input
                        type="text"
                        id="reportSearch"
                        class="form-control search-input"
                        placeholder="Search reports by name or description...">

                    <button
                        type="button"
                        id="clearSearch"
                        class="btn-clear d-none">

                        <i class="fas fa-times"></i>

                    </button>

                </div>

                <div class="mt-3">

                    <small
                        id="reportStats"
                        class="text-white">

                        {{ $totalReports }}
                        Reports

                        •

                        {{ $totalCategories }}
                        Categories

                        •

                        PDF & Excel Ready

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ================= REPORT CATEGORIES ================= --}}

    @foreach($sections as $section)

        <div class="card shadow-sm border-0 mb-4 report-section">

            <div class="card-header bg-{{ $section['color'] }} text-white">

                <h5 class="mb-0">

                    <i class="fas {{ $section['icon'] }} me-2"></i>

                    {{ $section['title'] }}

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    @foreach($section['reports'] as $report)

                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 report-column">

                            <div
                                class="report-card"

                                data-name="{{ strtolower($report['title']) }}"

                                data-description="{{ strtolower($report['desc']) }}">

                                <div class="report-icon">

                                    <i class="fas {{ $report['icon'] }}"></i>

                                </div>

                                <h5 class="report-title">

                                    {{ $report['title'] }}

                                </h5>

                                <p class="report-description">

                                    {{ $report['desc'] }}

                                </p>

                                @if(!empty($report['route']))
                                    <a href="{{ route($report['route']) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Open Report
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-clock me-1"></i>
                                        Coming Soon
                                    </button>
                                @endif
                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endforeach


    {{-- ================= NO RESULT ================= --}}

    <div
        id="noReports"
        class="text-center py-5 d-none">

        <i
            class="fas fa-search fa-4x text-muted mb-3">
        </i>

        <h3>

            No Reports Found

        </h3>

        <p class="text-muted">

            Try searching with another keyword.

        </p>

    </div>

</div>

@endsection

@push('styles')

<style>

/*====================================================
=            REPORT DASHBOARD STYLES
====================================================*/

.report-header{

    background:linear-gradient(135deg,#4f46e5,#7c3aed);

    border-radius:18px;

    color:#fff;

    padding:30px;

}

.report-header h2{

    font-size:30px;

    font-weight:700;

}

.report-header p{

    margin:0;

    opacity:.92;

}

.report-header-icon{

    width:70px;

    height:70px;

    border-radius:18px;

    background:rgba(255,255,255,.18);

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:32px;

    backdrop-filter:blur(10px);

}


/*====================================================
=            SEARCH BAR
====================================================*/

.search-wrapper{

    position:relative;

    max-width:550px;

}

.search-input{

    height:54px;

    border:none;

    border-radius:14px;

    padding-left:52px;

    padding-right:50px;

    font-size:15px;

    box-shadow:0 8px 25px rgba(0,0,0,.15);

    transition:.3s;

}

.search-input:focus{

    outline:none;

    border:none;

    box-shadow:

        0 0 0 4px rgba(255,255,255,.25),

        0 8px 30px rgba(0,0,0,.18);

}

.search-icon{

    position:absolute;

    left:18px;

    top:50%;

    transform:translateY(-50%);

    color:#5b6be8;

    font-size:18px;

    z-index:5;

}

.btn-clear{

    position:absolute;

    right:14px;

    top:50%;

    transform:translateY(-50%);

    width:30px;

    height:30px;

    border:none;

    border-radius:50%;

    background:#f3f4f6;

    color:#666;

    transition:.25s;

}

.btn-clear:hover{

    background:#ef4444;

    color:#fff;

}


/*====================================================
=            SECTION HEADER
====================================================*/

.report-section{

    border-radius:16px;

    overflow:hidden;

}

.report-section .card-header{

    padding:15px 20px;

    font-size:17px;

    font-weight:600;

}


/*====================================================
=            REPORT CARD
====================================================*/

.report-card{

    background:#fff;

    border-radius:16px;

    padding:28px;

    text-align:center;

    border:1px solid #edf0f7;

    transition:all .35s ease;

    height:100%;

    position:relative;

    overflow:hidden;

    box-shadow:0 6px 18px rgba(0,0,0,.05);

}

.report-card:hover{

    transform:translateY(-8px);

    box-shadow:0 18px 35px rgba(0,0,0,.15);

}

.report-card::before{

    content:"";

    position:absolute;

    left:0;

    top:0;

    width:100%;

    height:5px;

    background:#4f46e5;

    transform:scaleX(0);

    transition:.35s;

}

.report-card:hover::before{

    transform:scaleX(1);

}


/*====================================================
=            REPORT ICON
====================================================*/

.report-icon{

    width:82px;

    height:82px;

    border-radius:50%;

    background:#eef2ff;

    color:#4f46e5;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:auto;

    margin-bottom:22px;

    font-size:34px;

    transition:.3s;

}

.report-card:hover .report-icon{

    transform:rotate(8deg) scale(1.08);

    background:#4f46e5;

    color:#fff;

}


/*====================================================
=            TYPOGRAPHY
====================================================*/

.report-title{

    font-size:18px;

    font-weight:700;

    margin-bottom:10px;

}

.report-description{

    color:#6b7280;

    min-height:48px;

    font-size:14px;

}


/*====================================================
=            BUTTON
====================================================*/

.report-card .btn{

    margin-top:10px;

    border-radius:10px;

    padding:8px 18px;

    font-size:14px;

    transition:.25s;

}

.report-card .btn:hover{

    transform:translateY(-2px);

}


/*====================================================
=            STATS
====================================================*/

#reportStats{

    font-size:14px;

    letter-spacing:.3px;

}


/*====================================================
=            NO RESULT
====================================================*/

#noReports{

    background:#fff;

    border-radius:18px;

    border:1px dashed #d1d5db;

    box-shadow:0 8px 20px rgba(0,0,0,.05);

}

#noReports h3{

    font-weight:700;

}

#noReports p{

    margin-bottom:0;

}


/*====================================================
=            HIGHLIGHT
====================================================*/

mark{

    background:#fff3a3;

    color:#000;

    padding:2px 4px;

    border-radius:4px;

}


/*====================================================
=            RESPONSIVE
====================================================*/

@media(max-width:991px){

    .report-header{

        padding:25px;

    }

    .report-header h2{

        font-size:24px;

    }

    .report-header-icon{

        width:72px;
        height:72px;

        border-radius:18px;

        background:rgba(255,255,255,.18);

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:32px;

        margin-right:20px;

        backdrop-filter:blur(12px);

    }

    .search-wrapper{

        width:100%;
        max-width:620px;
        margin-top:28px;

    }

}

@media(max-width:576px){

    .report-header{

        text-align:center;

    }

    .report-header .d-flex{

        flex-direction:column;

    }

    .report-header-icon{

        margin-bottom:15px;

    }

}

</style>

@endpush

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("reportSearch");
    const clearButton = document.getElementById("clearSearch");
    const stats = document.getElementById("reportStats");
    const noReports = document.getElementById("noReports");

    const cards = document.querySelectorAll(".report-card");
    const sections = document.querySelectorAll(".report-section");

    const totalReports = cards.length;
    const totalCategories = sections.length;

    function escapeRegExp(string) {

        return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

    }

    function removeHighlights(element) {

        element.innerHTML = element.textContent;

    }

    function highlight(element, keyword) {

        if (keyword === "") return;

        const escaped = escapeRegExp(keyword);

        const regex = new RegExp("(" + escaped + ")", "gi");

        element.innerHTML = element.textContent.replace(
            regex,
            "<mark>$1</mark>"
        );

    }

    function filterReports() {

        const keyword = searchInput.value.trim().toLowerCase();

        let visibleReports = 0;
        let visibleSections = 0;

        clearButton.classList.toggle(
            "d-none",
            keyword === ""
        );

        sections.forEach(function(section){

            let sectionVisible = false;

            const reportColumns = section.querySelectorAll(".report-column");

            reportColumns.forEach(function(column){

                const card = column.querySelector(".report-card");

                const title = card.querySelector(".report-title");
                const description = card.querySelector(".report-description");

                removeHighlights(title);
                removeHighlights(description);

                const name = card.dataset.name;
                const desc = card.dataset.description;

                const matched =
                    name.includes(keyword) ||
                    desc.includes(keyword);

                if(matched){

                    column.style.display = "";

                    sectionVisible = true;

                    visibleReports++;

                    highlight(title, keyword);
                    highlight(description, keyword);

                }else{

                    column.style.display = "none";

                }

            });

            section.style.display = sectionVisible ? "" : "none";

            if(sectionVisible){

                visibleSections++;

            }

        });

        if(keyword === ""){

            stats.innerHTML =
                totalReports +
                " Reports • " +
                totalCategories +
                " Categories • PDF & Excel Ready";

        }else{

            stats.innerHTML =
                visibleReports +
                " matching report(s) in " +
                visibleSections +
                " categor" +
                (visibleSections === 1 ? "y" : "ies");

        }

        noReports.classList.toggle(
            "d-none",
            visibleReports !== 0
        );

    }

    searchInput.addEventListener("keyup", filterReports);

    clearButton.addEventListener("click", function(){

        searchInput.value = "";

        filterReports();

        searchInput.focus();

    });

    document.addEventListener("keydown", function(e){

        if(e.ctrlKey && e.key.toLowerCase() === "k"){

            e.preventDefault();

            searchInput.focus();

        }

    });

});

</script>

@endpush