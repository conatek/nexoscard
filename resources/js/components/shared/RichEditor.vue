<template>
    <div class="rich-editor" :class="{ 'rich-editor--focused': isFocused }">
        <div class="editor-toolbar" v-if="editor">
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :class="{ active: editor.isActive('bold') }"
                title="Negrita"
            >
                <i class="fa fa-bold"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="{ active: editor.isActive('italic') }"
                title="Cursiva"
            >
                <i class="fa fa-italic"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleUnderline().run()"
                :class="{ active: editor.isActive('underline') }"
                title="Subrayado"
            >
                <i class="fa fa-underline"></i>
            </button>
            <span class="toolbar-divider"></span>
            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ active: editor.isActive('bulletList') }"
                title="Lista con vinetas"
            >
                <i class="fa fa-list-ul"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ active: editor.isActive('orderedList') }"
                title="Lista numerada"
            >
                <i class="fa fa-list-ol"></i>
            </button>
            <span class="toolbar-divider"></span>
            <button
                type="button"
                @click="setLink"
                :class="{ active: editor.isActive('link') }"
                title="Enlace"
            >
                <i class="fa fa-link"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().unsetAllMarks().clearNodes().run()"
                title="Limpiar formato"
            >
                <i class="fa fa-eraser"></i>
            </button>
        </div>
        <EditorContent :editor="editor" class="editor-content" />
    </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Underline from '@tiptap/extension-underline'

export default {
    name: 'RichEditor',
    components: { EditorContent },
    props: {
        modelValue: { type: String, default: '' },
        placeholder: { type: String, default: 'Escribe aqui...' },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            editor: null,
            isFocused: false,
        }
    },
    watch: {
        modelValue(value) {
            const isSame = this.editor.getHTML() === value
            if (!isSame) this.editor.commands.setContent(value, false)
        },
    },
    mounted() {
        this.editor = new Editor({
            content: this.modelValue,
            extensions: [
                StarterKit.configure({
                    heading: false,
                    codeBlock: false,
                    blockquote: false,
                    horizontalRule: false,
                }),
                Link.configure({ openOnClick: false }),
                Underline,
            ],
            onUpdate: () => {
                const html = this.editor.getHTML()
                this.$emit('update:modelValue', html === '<p></p>' ? '' : html)
            },
            onFocus: () => { this.isFocused = true },
            onBlur: () => { this.isFocused = false },
        })
    },
    beforeUnmount() {
        this.editor.destroy()
    },
    methods: {
        setLink() {
            if (this.editor.isActive('link')) {
                this.editor.chain().focus().unsetLink().run()
                return
            }
            const url = window.prompt('URL del enlace:')
            if (url) {
                this.editor.chain().focus().setLink({ href: url }).run()
            }
        },
    },
}
</script>

<style scoped>
.rich-editor {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.rich-editor--focused {
    border-color: #7c3aed;
    box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.1);
}

.editor-toolbar {
    padding: 6px 8px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 8px 8px 0 0;
    display: flex;
    gap: 2px;
    flex-wrap: wrap;
}

.editor-toolbar button {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-radius: 6px;
    color: #64748b;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.editor-toolbar button:hover {
    background: #f5f3ff;
    color: #7c3aed;
}

.editor-toolbar button.active {
    background: #f5f3ff;
    color: #7c3aed;
}

.toolbar-divider {
    width: 1px;
    height: 24px;
    background: #e2e8f0;
    margin: 4px 4px;
    align-self: center;
}

.editor-content :deep(.tiptap) {
    padding: 10px 12px;
    min-height: 120px;
    outline: none;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #1e293b;
}

.editor-content :deep(.tiptap p) {
    margin: 0 0 0.5rem 0;
}

.editor-content :deep(.tiptap p:last-child) {
    margin-bottom: 0;
}

.editor-content :deep(.tiptap ul),
.editor-content :deep(.tiptap ol) {
    padding-left: 1.5em;
    margin: 0.5rem 0;
}

.editor-content :deep(.tiptap li p) {
    margin: 0;
}

.editor-content :deep(.tiptap a) {
    color: #7c3aed;
    text-decoration: underline;
}
</style>
