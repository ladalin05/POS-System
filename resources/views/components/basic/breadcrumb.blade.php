<!-- Page header -->
<div class="user-header-container d-flex flex-column flex-md-row justify-content-between align-items-center p-3 ">
    <div class="header-titles">
        {{ $title ?? 'List' }}
    </div>
    {{ $slot }}
</div>
<!-- /page header -->
