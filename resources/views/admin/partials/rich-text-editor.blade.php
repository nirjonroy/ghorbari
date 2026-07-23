@once
  @push('styles')
    <style>
      .tox.tox-tinymce {
        border-color: #dee2e6;
        border-radius: 0.375rem;
        min-height: 360px;
      }

      .tox .tox-menubar,
      .tox .tox-toolbar,
      .tox .tox-toolbar__overflow,
      .tox .tox-toolbar__primary {
        background: #fff;
      }

      .tox .tox-statusbar {
        border-top-color: #dee2e6;
      }
    </style>
  @endpush

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        if (!window.tinymce) {
          return;
        }

        tinymce.init({
          selector: 'textarea.rich-text-editor',
          height: 420,
          min_height: 320,
          menubar: 'file edit view insert format tools table help',
          plugins: 'anchor autolink charmap code codesample fullscreen help image link lists media searchreplace table visualblocks wordcount',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image media | fullscreen code help',
          font_family_formats: 'System UI=system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Merriweather=merriweather,serif;Source Sans Pro=source sans pro,sans-serif;Times New Roman=times new roman,times,serif',
          font_size_formats: '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 48px',
          branding: false,
          promotion: false,
          convert_urls: false,
          relative_urls: false,
          license_key: 'gpl'
        });

        document.querySelectorAll('form').forEach((form) => {
          form.addEventListener('submit', () => {
            tinymce.triggerSave();
          });
        });
      });
    </script>
  @endpush
@endonce
