<div class="form-group" >
    <div class="{{ $errors->has('anio') ? ' has-error' : '' }}">
        <label class="control-label" for="anio">Año *</label>
        <input class="form-control" type="text" name="anio" id="anio" maxlength="4" value="{{ (!empty(old('anio'))) ? old('anio') : '' }}" required>
        @if ($errors->has('anio'))
            <span class="help-block">
                <strong>{{ $errors->first('anio') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('mes') ? ' has-error' : '' }}">
        <label class="control-label" for="mes">Mes *</label>
        <select class="form-control" name="mes" id="mes" required>
            <option value="1" {{ (!empty(old('mes')) && old('mes') == 1) ? 'selected' : '' }}>Enero</option>
            <option value="2" {{ (!empty(old('mes')) && old('mes') == 2) ? 'selected' : '' }}>Febrero</option>
            <option value="3" {{ (!empty(old('mes')) && old('mes') == 3) ? 'selected' : '' }}>Marzo</option>
            <option value="4" {{ (!empty(old('mes')) && old('mes') == 4) ? 'selected' : '' }}>Abril</option>
            <option value="5" {{ (!empty(old('mes')) && old('mes') == 5) ? 'selected' : '' }}>Mayo</option>
            <option value="6" {{ (!empty(old('mes')) && old('mes') == 6) ? 'selected' : '' }}>Junio</option>
            <option value="7" {{ (!empty(old('mes')) && old('mes') == 7) ? 'selected' : '' }}>Julio</option>
            <option value="8" {{ (!empty(old('mes')) && old('mes') == 8) ? 'selected' : '' }}>Agosto</option>
            <option value="9" {{ (!empty(old('mes')) && old('mes') == 9) ? 'selected' : '' }}>Septiembre</option>
            <option value="10" {{ (!empty(old('mes')) && old('mes') == 10) ? 'selected' : '' }}>Octubre</option>
            <option value="11" {{ (!empty(old('mes')) && old('mes') == 11) ? 'selected' : '' }}>Noviembre</option>
            <option value="12" {{ (!empty(old('mes')) && old('mes') == 12) ? 'selected' : '' }}>Diciembre</option>
        </select>
        @if ($errors->has('mes'))
            <span class="help-block">
                <strong>{{ $errors->first('mes') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('tipo') ? ' has-error' : '' }}">
        <label class="control-label" for="tipo">Tipo *</label>
        <select class="form-control" name="tipo" id="tipo" required>
            <option value="1" {{ (!empty(old('tipo')) && old('tipo') == 1) ? 'selected' : '' }}>Normal</option>
            <option value="2" {{ (!empty(old('tipo')) && old('tipo') == 2) ? 'selected' : '' }}>Adicional</option>
            <option value="3" {{ (!empty(old('tipo')) && old('tipo') == 3) ? 'selected' : '' }}>SAC</option>
        </select>
        @if ($errors->has('tipo'))
            <span class="help-block">
                <strong>{{ $errors->first('tipo') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('numero') ? ' has-error' : '' }}">
        <label class="control-label" for="numero">Numero</label>
        <input class="form-control" type="text" name="numero" id="numero" value="{{ (!empty(old('numero'))) ? old('numero') : '' }}">
        @if ($errors->has('numero'))
            <span class="help-block">
                <strong>{{ $errors->first('numero') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('grupo') ? ' has-error' : '' }}">
        <label class="control-label" for="grupo">Grupo *</label>
        <select class="form-control" name="grupo" id="grupo" required>
            <option value="0">Planta funcional (Of)</option>
        </select>
        @if ($errors->has('grupo'))
            <span class="help-block">
                <strong>{{ $errors->first('grupo') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('nivel') ? ' has-error' : '' }}">
        <label class="control-label" for="nivel">Nivel *</label>
        <select class="form-control" name="nivel" id="nivel" required>
            <option value="0" {{ (!empty(old('nivel')) && old('nivel') == 0) ? 'selected' : '' }}>Sin nivel</option>
            <option value="1" {{ (!empty(old('nivel')) && old('nivel') == 1) ? 'selected' : '' }}>Inicial</option>
            <option value="2" {{ (!empty(old('nivel')) && old('nivel') == 2) ? 'selected' : '' }}>Primario</option>
            <option value="3" {{ (!empty(old('nivel')) && old('nivel') == 3) ? 'selected' : '' }}>Medio</option>
            <option value="4" {{ (!empty(old('nivel')) && old('nivel') == 4) ? 'selected' : '' }}>Superior</option>
        </select>
        @if ($errors->has('nivel'))
            <span class="help-block">
                <strong>{{ $errors->first('nivel') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group" >
    <div class="{{ $errors->has('archivo') ? ' has-error' : '' }}">
        <label class="control-label" for="archivo">Archivo *</label>
        <input class="form-control" type="file" name="archivo" id="archivo" required>
        @if ($errors->has('archivo'))
            <span class="help-block">
                <strong>{{ $errors->first('archivo') }}</strong>
            </span>
        @endif
    </div>
</div>
