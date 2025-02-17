@extends('layouts.pages.create')

@section('style')
<style>
    .attachment-preview-container {
        position: relative;
        display: none; /* Initially hide the container */
        margin-top: 10px;
    }
    .attachment-preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
    }
    .attachment-preview-container:hover .attachment-preview-overlay {
        opacity: 1;
    }
    .delete-btn {
        background: red;
        border: none;
        color: white;
        padding: 5px 10px;
        cursor: pointer;
        display: none; /* Initially hide the delete button */
    }
</style>
@endsection

@section('form')

<div class="{{ config('setting.form_group_class') }}">
    <label for="locale" class="{{ config('setting.form_label_class') }}">{{ __('locale') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('locale') is-invalid @enderror"
            type="text"
            name="locale"
            id="locale"
            value="{{ old('locale') }}"
            placeholder="{{ __('enter_locale') }}"
            required
        >
        @error('locale')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="translations" class="{{ config('setting.form_label_class') }}">{{ __('translations') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('translations') is-invalid @enderror"
            type="text"
            name="translations"
            id="translations"
            value="{{ old('translations') }}"
            placeholder="{{ __('enter_translations') }}"
            required
        >
        @error('translations')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="attachment" class="{{ config('setting.form_label_class') }}">{{ __('attachment_image') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('attachment') is-invalid @enderror"
            type="file"
            name="attachment"
            id="attachment"
            accept="image/*"
            onchange="previewLogo(event)"
            required
        >
        @error('attachment')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <div class="attachment-preview-container">
            <img id="attachment-preview" src="#" alt="{{ __('attachment_preview') }}" style="display: none; max-height: 200px;">
            <div class="attachment-preview-overlay">
                <a href="#" class="text-danger" style="font-size: 2.5rem;" onclick="deleteAttachmentPreview()">&times;</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function previewLogo(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('attachment-preview');
            output.src = reader.result;
            output.style.display = 'block';
            document.querySelector('.attachment-preview-container').style.display = 'inline-block'; // Show the container
            document.querySelector('.delete-btn').style.display = 'block'; // Show the delete button
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function deleteAttachmentPreview() {
        var preview = document.getElementById('attachment-preview');
        preview.src = '#';
        preview.style.display = 'none';
        document.getElementById('attachment').value = '';
        document.querySelector('.attachment-preview-container').style.display = 'none'; // Hide the container
        document.querySelector('.delete-btn').style.display = 'none'; // Hide the delete button
    }
</script>
@endsection
