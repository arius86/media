{% do assets.addJs('assets/vendor/jquery-uploader/dist/jquery-uploader.min.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/upload.js') %}
<input type="file"{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} value="{{ value }}" vegas-cmf="upload" />