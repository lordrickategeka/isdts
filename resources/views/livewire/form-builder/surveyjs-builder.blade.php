@extends('layouts.app')

@push('styles')
    <link href="https://unpkg.com/survey-creator-core@1.9.124/survey-creator-core.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://unpkg.com/survey-creator-core@1.9.124/survey-creator-core.min.js"></script>
@endpush
@section('content')

<div>
    <div id="surveyjs-creator-container" style="height: 80vh;"></div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const creator = new SurveyCreator.SurveyCreator({
            showLogicTab: true,
            isAutoSave: false,
            showTranslationTab: true
        });
        creator.render("surveyjs-creator-container");
        creator.saveSurveyFunc = function (saveNo, callback) {
            const surveyJson = creator.text;
            fetch("{{ route('surveyjs.builder.save') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ json: surveyJson })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Survey saved successfully!');
                } else {
                    alert('Failed to save survey.');
                }
                callback(saveNo, true);
            })
            .catch(() => {
                alert('Failed to save survey.');
                callback(saveNo, false);
            });
        };
    });
</script>
@endsection
