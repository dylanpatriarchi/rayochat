@extends('layouts.admin')

@section('title', 'Modifica Informazioni Aziendali')
@section('page-title', 'Modifica Informazioni Aziendali')
@section('page-description', 'Gestisci le informazioni aziendali del sito')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $site->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica le informazioni aziendali</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Torna al Sito
            </a>
        </div>
    </div>

    <!-- Editor Form -->
    <div class="card">
        <form method="POST" action="{{ route('admin.sites.update-info', $site) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="markdown_content" class="block text-sm font-medium text-gray-700 mb-2">
                    Informazioni Aziendali
                </label>
                <p class="text-sm text-gray-500 mb-4">
                    Usa la sintassi Markdown per formattare il testo. Esempi: **grassetto**, *corsivo*, ## Titolo, - Lista
                </p>
                
                <!-- Markdown Editor Container -->
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <!-- Toolbar -->
                    <div class="bg-gray-50 border-b border-gray-300 px-4 py-2 flex items-center space-x-2">
                        <button type="button" onclick="insertMarkdown('**', '**')" class="toolbar-btn" title="Grassetto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="insertMarkdown('*', '*')" class="toolbar-btn" title="Corsivo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4l4 16m-4-8h8"></path>
                            </svg>
                        </button>
                        <div class="border-l border-gray-300 h-6"></div>
                        <button type="button" onclick="insertMarkdown('## ', '')" class="toolbar-btn" title="Titolo">H2</button>
                        <button type="button" onclick="insertMarkdown('### ', '')" class="toolbar-btn" title="Sottotitolo">H3</button>
                        <div class="border-l border-gray-300 h-6"></div>
                        <button type="button" onclick="insertMarkdown('- ', '')" class="toolbar-btn" title="Lista">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="insertMarkdown('[', '](url)')" class="toolbar-btn" title="Link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </button>
                        <div class="border-l border-gray-300 h-6 ml-auto"></div>
                        <button type="button" onclick="togglePreview()" id="preview-btn" class="toolbar-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Anteprima
                        </button>
                    </div>
                    
                    <!-- Editor Area -->
                    <div class="relative">
                        <textarea 
                            id="markdown_content" 
                            name="markdown_content" 
                            rows="20" 
                            class="w-full p-4 border-0 resize-none focus:ring-0 font-mono text-sm"
                            placeholder="Inserisci qui le informazioni aziendali usando la sintassi Markdown...

Esempi:
## Chi Siamo
La nostra azienda è leader nel settore...

### I Nostri Servizi
- Consulenza specializzata
- Supporto tecnico 24/7
- Formazione personalizzata

**Contatti:**
- Email: info@esempio.com
- Telefono: +39 123 456 7890"
                        >{{ old('markdown_content', $siteInfo->markdown_content) }}</textarea>
                        
                        <!-- Preview Area (initially hidden) -->
                        <div id="preview-area" class="hidden w-full p-4 bg-white prose max-w-none">
                            <div id="preview-content"></div>
                        </div>
                    </div>
                </div>
                
                @error('markdown_content')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    Annulla
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salva Informazioni
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.toolbar-btn {
    @apply px-2 py-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded text-sm font-medium transition-colors;
}
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    @apply text-gray-900;
}
.prose p {
    @apply text-gray-700;
}
.prose ul, .prose ol {
    @apply text-gray-700;
}
.prose a {
    @apply text-orange-600 hover:text-orange-700;
}
</style>

<script>
let isPreviewMode = false;

function insertMarkdown(before, after) {
    const textarea = document.getElementById('markdown_content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    const newText = before + selectedText + after;
    textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
    
    // Set cursor position
    const newCursorPos = start + before.length + selectedText.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
    textarea.focus();
}

function togglePreview() {
    const textarea = document.getElementById('markdown_content');
    const previewArea = document.getElementById('preview-area');
    const previewContent = document.getElementById('preview-content');
    const previewBtn = document.getElementById('preview-btn');
    
    isPreviewMode = !isPreviewMode;
    
    if (isPreviewMode) {
        // Show preview
        textarea.classList.add('hidden');
        previewArea.classList.remove('hidden');
        previewBtn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Modifica
        `;
        
        // Convert markdown to HTML (simple conversion for preview)
        const markdownText = textarea.value;
        const htmlContent = convertMarkdownToHtml(markdownText);
        previewContent.innerHTML = htmlContent;
    } else {
        // Show editor
        textarea.classList.remove('hidden');
        previewArea.classList.add('hidden');
        previewBtn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Anteprima
        `;
        textarea.focus();
    }
}

function convertMarkdownToHtml(markdown) {
    let html = markdown;
    
    // Headers
    html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
    html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
    html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');
    
    // Bold
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    
    // Italic
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
    
    // Links
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>');
    
    // Lists
    html = html.replace(/^\- (.*$)/gim, '<li>$1</li>');
    html = html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');
    
    // Line breaks
    html = html.replace(/\n\n/g, '</p><p>');
    html = '<p>' + html + '</p>';
    
    // Clean up empty paragraphs
    html = html.replace(/<p><\/p>/g, '');
    html = html.replace(/<p>(<h[1-6]>)/g, '$1');
    html = html.replace(/(<\/h[1-6]>)<\/p>/g, '$1');
    html = html.replace(/<p>(<ul>)/g, '$1');
    html = html.replace(/(<\/ul>)<\/p>/g, '$1');
    
    return html;
}
</script>
@endsection
