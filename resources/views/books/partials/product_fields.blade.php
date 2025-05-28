{{-- Campos dinâmicos para cada tipo de produto --}}
<div id="product-fields-ebook" class="product-fields" style="display:none">
    <label for="file_format">Formato do Arquivo</label>
    <input type="text" name="file_format" id="file_format" placeholder="PDF, EPUB, MOBI" class="input-text">
    <label for="file_size">Tamanho do Arquivo (KB)</label>
    <input type="number" name="file_size" id="file_size" min="0" class="input-number">
    <label for="has_drm">Possui DRM?</label>
    <select name="has_drm" id="has_drm" class="input-select">
        <option value="0">Não</option>
        <option value="1">Sim</option>
    </select>
</div>
<div id="product-fields-box" class="product-fields" style="display:none">
    <label for="books_count">Quantidade de Livros</label>
    <input type="number" name="books_count" id="books_count" min="1" class="input-number">
    <label for="titles">Títulos Inclusos (um por linha)</label>
    <textarea name="titles" id="titles" rows="3" class="input-textarea"></textarea>
    <label for="extras">Extras/Brindes (um por linha)</label>
    <textarea name="extras" id="extras" rows="2" class="input-textarea"></textarea>
</div>
<div id="product-fields-gibi" class="product-fields" style="display:none">
    <label for="issue_number">Número da Edição</label>
    <input type="number" name="issue_number" id="issue_number" min="1" class="input-number">
    <label for="illustrator">Ilustrador</label>
    <input type="text" name="illustrator" id="illustrator" class="input-text">
    <label for="is_colored">Colorido?</label>
    <select name="is_colored" id="is_colored" class="input-select">
        <option value="1">Sim</option>
        <option value="0">Não</option>
    </select>
</div>
<div id="product-fields-fisico" class="product-fields" style="display:none">
    <div class="form-row">
        <div class="form-group">
            <label for="pages">Número de Páginas</label>
            <input type="number" name="pages" id="pages" min="1" class="input-number" placeholder="Ex: 320">
        </div>
        <div class="form-group">
            <label for="cover_type">Tipo de Capa</label>
            <input type="text" name="cover_type" id="cover_type" class="input-text" placeholder="Brochura, Dura...">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="weight">Peso (g)</label>
            <input type="number" name="weight" id="weight" min="0" step="0.01" class="input-number" placeholder="Ex: 350">
        </div>
        <div class="form-group">
            <label for="dimensions">Dimensões (LxAxP em cm)</label>
            <input type="text" name="dimensions" id="dimensions" class="input-text" placeholder="Ex: 15x23x2">
        </div>
    </div>
</div>
<script>
    function showProductFields() {
        const type = document.getElementById('product_type').value;
        document.querySelectorAll('.product-fields').forEach(el => el.style.display = 'none');
        if(document.getElementById('product-fields-' + type)) {
            document.getElementById('product-fields-' + type).style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', showProductFields);
    document.getElementById('product_type').addEventListener('change', showProductFields);
</script>
