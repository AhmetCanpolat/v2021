<div class="form-group">
    <div class="row">
        <label class="col-sm-2 control-label" for="name">Adınız</label>
        <div class="col-sm-10">
            <input type="text" placeholder="Adınız" id="name" name="name" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="email">e-posta adresiniz</label>
        <div class="col-sm-10">
            <input type="email" placeholder="E-posta adresi" id="email" name="email" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="address">Adres</label>
        <div class="col-sm-10">
            <textarea placeholder="Adres" id="address" name="address" class="form-control" required></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="email">Ülke</label>
        <div class="col-sm-10">
            <select name="country" id="country" class="form-control demo-select2" required data-placeholder="Ülke seçin">
                @foreach (\App\Country::where('status',1)->get() as $key => $country)
                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="city">Şehir</label>
        <div class="col-sm-10">
            <input type="text" placeholder="Şehir" id="city" name="city" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="postal_code">Posta kodu</label>
        <div class="col-sm-10">
            <input type="number" min="0" placeholder="Posta kodu" id="postal_code" name="postal_code" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="phone">Telefon</label>
        <div class="col-sm-10">
            <input type="number" min="0" placeholder="Telefon" id="phone" name="phone" class="form-control" required>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.demo-select2').select2();
</script>
