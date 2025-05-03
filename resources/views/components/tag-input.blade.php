@props(['tags' => [], 'wireModel' => '', 'variable'=>'form.tags'])

@php
    $defaultTags = json_encode($tags);
    $whitelist = json_encode($tags);
@endphp

<div wire:ignore
     x-data="{
        tagify: null,

        init() {
            this.$nextTick(() => {
                this.initializeTagify();
            });
        },

        initializeTagify() {
            const input = this.$refs.input;

            if (this.tagify) {
                this.tagify.destroy();
            }

            this.tagify = new Tagify(input, {
                whitelist: {{ $whitelist }},
                dropdown: {
                    mapValueTo: 'full',
                    classname: 'tagify__dropdown--rtl',
                    enabled: 0,
                    RTL: true,
                    escapeHTML: false
                },
                duplicates: false,
                skipInvalid: false,
                keepInvalidTags: false,
                editTags: false,
                maxTags: undefined,
                backspace: true,
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value)
            });

            // تنظیم مقادیر اولیه
            const defaultTags = {{ $defaultTags }};
            if (defaultTags && defaultTags.length > 0) {
                this.tagify.loadOriginalValues(defaultTags);
            }

            // هندل کردن تغییرات
            this.tagify.on('add', (e) => {
                const currentTags = this.tagify.value.map(tag => tag.value);
                @this.set('{{ $variable }}', currentTags);
            });

            this.tagify.on('remove', (e) => {
                const currentTags = this.tagify.value.map(tag => tag.value);
                @this.set('{{ $variable }}', currentTags);
            });

            this.tagify.on('invalid', (e) => {
                if (e.detail.message === 'duplicate') {
                    console.log('این تگ قبلاً اضافه شده است');
                }
            });
        }
    }"
>
    <style>
        .tag-container {
            display: flex;
            align-items: center;
            gap: 5px;
            width: 100%;
        }

        .tagify--rtl {
            color: black;
            background-color: white;
            width: 100%;
            box-sizing: border-box;
            border-radius: 8px;
            padding: 1px;
            border: 1px solid #ccc;
        }

        @media (prefers-color-scheme: dark) {
            .tagify--rtl {
                color: white;
                background-color: #111827;
                border-color: #555;
            }
        }
    </style>
    <div class="tag-container" wire:ignore>
        <input name='rtl' class='tagify--rtl' placeholder='کلیدواژه ها را انتخاب کنید' x-ref="input">
    </div>
</div>

<script>
    // برای اطمینان از اجرای مجدد بعد از navigation
    document.addEventListener('livewire:navigated', () => {
        Alpine.initTree(document.body);
    });
</script>

