{{-- KTP (city ke bawah — province sudah handle sendiri via TomSelect.getValue) --}}
<input type="hidden" id="old_city_code"     value="{{ old('city_code',     $pendaftaran->city_code     ?? '') }}">
<input type="hidden" id="old_district_code" value="{{ old('district_code', $pendaftaran->district_code ?? '') }}">
<input type="hidden" id="old_village_code"  value="{{ old('village_code',  $pendaftaran->village_code  ?? '') }}">
 
{{-- Lahir --}}
<input type="hidden" id="old_city_code_lahir" value="{{ old('city_code_lahir', $pendaftaran->city_code_lahir ?? '') }}">
 
{{-- Sekarang --}}
<input type="hidden" id="old_city_code_sekarang"     value="{{ old('city_code_sekarang',     $pendaftaran->city_code_sekarang     ?? '') }}">
<input type="hidden" id="old_district_code_sekarang" value="{{ old('district_code_sekarang', $pendaftaran->district_code_sekarang ?? '') }}">
<input type="hidden" id="old_village_code_sekarang"  value="{{ old('village_code_sekarang',  $pendaftaran->village_code_sekarang  ?? '') }}">