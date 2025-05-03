<style>
    .tox-promotion {
        display: none !important;
    }

    .tox-statusbar__branding {
        display: none !important;
    }
</style>
<div>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        // Function to initialize TinyMCE
        function initTinyMCE() {
            // Initialize TinyMCE
            if (window.tinymce) {
                tinymce.init({
                    selector: '.tinymce-editor',
                    language: 'fa',
                    skin: "oxide-dark",
                    content_css: "dark",
                    plugins: 'code table lists link autolink autosave image media preview save wordcount fullscreen searchreplace visualblocks visualchars nonbreaking pagebreak charmap anchor insertdatetime advlist help',
                    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview media | ltr rtl | code | advcode | advtable | template',
                    setup: function (editor) {
                        editor.on('change keyup', function () {
                            // Get the editor ID
                            const editorId = editor.id;
                            // Get the corresponding hidden input ID
                            const inputId = editorId + '-input';
                            // Update the hidden input value
                            document.getElementById(inputId).value = editor.getContent();
                            // Dispatch an input event to trigger Livewire updates
                            document.getElementById(inputId).dispatchEvent(new Event('input', {bubbles: true}));
                        });
                    }
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            initTinyMCE();

            Livewire.on('tinymce-reinit', () => {
                // Small delay to ensure DOM is updated
                initTinyMCE();
                setTimeout(function () {
                    initTinyMCE();
                }, 100);
            });
        });

        // Initialize after Livewire navigation
        document.addEventListener('livewire:navigated', function () {
            // Small delay to ensure DOM is updated
            setTimeout(function () {
                initTinyMCE();
            }, 100);
        });


    </script>
</div>
