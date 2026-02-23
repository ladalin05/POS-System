@props(['value'])

<span class="badge {{ $value ? 'bg-success' : 'bg-danger' }}">
    {{ $value ? 'Yes' : 'No' }}
</span>
