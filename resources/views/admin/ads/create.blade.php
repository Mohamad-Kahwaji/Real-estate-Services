@extends('layouts/contentNavbarLayout')
@section('title', __('app.add_ad'))

@section('content')
<style>
.form-card {
    background:#fff; border-radius:20px; border:1px solid #f0eef8;
    box-shadow:0 4px 24px rgba(105,108,255,.08); padding:28px 32px; max-width:760px;
}
.form-card-title {
    font-size:15px; font-weight:800; color:#312d4b;
    display:flex; align-items:center; gap:10px;
    margin-bottom:24px; padding-bottom:14px; border-bottom:1px solid #f5f4f8;
}
.form-card-title i { color:#696cff; font-size:18px; }
.f-label { font-size:12px; font-weight:700; color:#97939e; text-transform:uppercase; letter-spacing:.06em; margin-bottom:7px; display:block; }
.f-input {
    width:100%; padding:12px 16px; border:1.5px solid #e5e2ec; border-radius:12px;
    font-size:14px; color:#312d4b; background:#faf9fc; outline:none; transition:.2s;
}
.f-input:focus { border-color:#696cff; background:#fff; box-shadow:0 0 0 4px rgba(105,108,255,.1); }
.img-preview-wrap {
    border:2px dashed #d4d5ff; border-radius:14px; padding:20px;
    text-align:center; cursor:pointer; transition:.2s; background:#faf9fc;
}
.img-preview-wrap:hover { border-color:#696cff; background:#f0f1ff; }
.img-preview { max-height:200px; border-radius:10px; display:none; margin:0 auto; }
.btn-save {
    padding:12px 28px; border-radius:12px; border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff; font-size:14px; font-weight:700; cursor:pointer;
    box-shadow:0 4px 16px rgba(105,108,255,.35); transition:.2s;
    display:inline-flex; align-items:center; gap:8px;
}
.btn-save:hover { transform:translateY(-1px); }
.toggle-switch { display:flex; align-items:center; gap:12px; }
.toggle-switch input[type=checkbox] { width:42px; height:22px; accent-color:#696cff; cursor:pointer; }
</style>

<div style="margin-bottom:20px;">
    <a href="{{ route('ads.index') }}" style="color:#696cff;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <i class="ri ri-arrow-right-s-line"></i> {{ __('app.ads') }}
    </a>
</div>

<div class="form-card">
    <div class="form-card-title">
        <i class="ri ri-advertisement-line"></i> {{ __('app.add_ad') }}
    </div>

    @if($errors->any())
    <div style="background:#fdeaea;border-radius:12px;padding:12px 16px;font-size:13px;color:#a53030;margin-bottom:20px;">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">

            {{-- Title --}}
            <div class="col-12">
                <label class="f-label">{{ __('app.ad_title') }}</label>
                <input type="text" name="title" class="f-input" value="{{ old('title') }}"
                       placeholder="{{ __('app.ad_title_ph') }}" required>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label class="f-label">{{ __('app.description') }}</label>
                <textarea name="description" class="f-input" rows="3"
                          placeholder="{{ __('app.ad_desc_ph') }}" style="resize:vertical;">{{ old('description') }}</textarea>
            </div>

            {{-- Link --}}
            <div class="col-12">
                <label class="f-label">{{ __('app.ad_link') }} <span style="font-weight:400;text-transform:none;">({{ __('app.optional') }})</span></label>
                <div style="position:relative;">
                    <i class="ri ri-link" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#b0aab8;font-size:16px;"></i>
                    <input type="url" name="link" class="f-input" value="{{ old('link') }}"
                           placeholder="https://example.com" style="padding-left:40px;">
                </div>
            </div>

            {{-- Image --}}
            <div class="col-12">
                <label class="f-label">{{ __('app.ad_image') }}</label>
                <div class="img-preview-wrap" onclick="document.getElementById('imgInput').click()">
                    <img id="imgPreview" class="img-preview" src="" alt="">
                    <div id="imgPlaceholder">
                        <i class="ri ri-image-add-line" style="font-size:36px;color:#c5c7ff;display:block;margin-bottom:8px;"></i>
                        <div style="font-size:13px;color:#97939e;">{{ __('app.click_to_upload') }}</div>
                        <div style="font-size:11px;color:#b0aab8;margin-top:4px;">JPG, PNG, WEBP — {{ __('app.max_3mb') }}</div>
                    </div>
                </div>
                <input type="file" id="imgInput" name="image" accept="image/*" style="display:none;" required
                       onchange="previewImg(this)">
            </div>

            {{-- Active toggle --}}
            <div class="col-12">
                <label class="f-label">{{ __('app.status') }}</label>
                <div class="toggle-switch">
                    <input type="checkbox" name="is_active" id="activeToggle" checked>
                    <label for="activeToggle" style="font-size:14px;font-weight:600;color:#585164;cursor:pointer;">
                        {{ __('app.active_on_publish') }}
                    </label>
                </div>
            </div>

        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-save">
                <i class="ri ri-save-line"></i> {{ __('app.save') }}
            </button>
            <a href="{{ route('ads.index') }}" style="
                padding:12px 20px;border-radius:12px;border:1.5px solid #e5e2ec;
                background:#fff;color:#97939e;font-size:14px;font-weight:700;
                text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                {{ __('app.cancel') }}
            </a>
        </div>
    </form>
</div>

<script>
function previewImg(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('imgPreview');
        img.src = e.target.result;
        img.style.display = 'block';
        document.getElementById('imgPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
