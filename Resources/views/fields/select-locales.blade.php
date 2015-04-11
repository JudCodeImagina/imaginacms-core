<div class="form-group">
    <label for="{{ $settingName }}">{{ trans($moduleInfo['description']) }}</label>
    <select multiple class="form-control" name="{{ $settingName }}[]" id="{{ $settingName }}">
        <?php foreach ($locales as $id => $locale): ?>
        <option value="{{ $id }}" {{ isset($dbSettings[$settingName]) && isset(array_flip(json_decode($dbSettings[$settingName]->plainValue))[$id]) ? 'selected' : '' }}>
            {{ array_get($locale, 'name') }}
        </option>
        <?php endforeach; ?>
    </select>
</div>
