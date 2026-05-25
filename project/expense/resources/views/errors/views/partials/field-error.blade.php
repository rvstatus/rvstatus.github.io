@if ($errors->has($field))
<span class="text-danger">
    <strong>{{ $errors->first($field) }}</strong>
</span>
@endif