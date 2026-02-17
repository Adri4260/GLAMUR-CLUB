// Datos de notas aromáticas
const PERFUME_NOTES = {
    top: [
        { id: 'citrus', name: 'Cítricos', icon: '🍊' },
        { id: 'bergamot', name: 'Bergamota', icon: '🍋' },
        { id: 'lavender', name: 'Lavanda', icon: '💜' },
        { id: 'mint', name: 'Menta', icon: '🌿' },
        { id: 'apple', name: 'Manzana', icon: '🍎' },
        { id: 'pear', name: 'Pera', icon: '🍐' }
    ],
    heart: [
        { id: 'rose', name: 'Rosa', icon: '🌹' },
        { id: 'jasmine', name: 'Jazmín', icon: '🌸' },
        { id: 'lily', name: 'Lirio', icon: '🌺' },
        { id: 'violet', name: 'Violeta', icon: '💐' },
        { id: 'iris', name: 'Iris', icon: '🪻' },
        { id: 'peony', name: 'Peonía', icon: '🌷' }
    ],
    base: [
        { id: 'vanilla', name: 'Vainilla', icon: '🍦' },
        { id: 'sandalwood', name: 'Sándalo', icon: '🪵' },
        { id: 'musk', name: 'Almizcle', icon: '✨' },
        { id: 'amber', name: 'Ámbar', icon: '🟡' },
        { id: 'patchouli', name: 'Pachulí', icon: '🍂' },
        { id: 'cedar', name: 'Cedro', icon: '🌲' }
    ]
};

// Estado del formulario de notas
const selectedNotes = {
    top: [],
    heart: [],
    base: []
};

// Renderizar notas
function renderNotes(type, container) {
    const notes = PERFUME_NOTES[type];
    container.innerHTML = notes.map(note => `
        <div class="note-card" data-note="${note.id}" onclick="toggleNote('${type}', '${note.id}')">
            <div class="note-icon">${note.icon}</div>
            <div class="note-name">${note.name}</div>
            <div class="note-checkmark">✓</div>
        </div>
    `).join('');
}

// Toggle nota seleccionada
function toggleNote(type, noteId) {
    const card = document.querySelector(`[data-note="${noteId}"]`);
    card.classList.toggle('selected');
    
    const index = selectedNotes[type].indexOf(noteId);
    if (index > -1) {
        selectedNotes[type].splice(index, 1);
    } else {
        selectedNotes[type].push(noteId);
    }
}

// Tabs
const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        tabBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const tabId = btn.dataset.tab + 'Tab';
        tabContents.forEach(content => content.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
    });
});

// Formulario de notas
const notesForm = document.getElementById('notesForm');
notesForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validación cliente
    const nombre = document.getElementById('perfumeName1').value.trim();
    let hasErrors = false;
    
    // Limpiar errores
    document.getElementById('name1Error').textContent = '';
    document.getElementById('topNotesError').textContent = '';
    document.getElementById('heartNotesError').textContent = '';
    document.getElementById('baseNotesError').textContent = '';
    
    if (nombre.length < 3) {
        document.getElementById('name1Error').textContent = 'El nombre debe tener al menos 3 caracteres';
        hasErrors = true;
    }
    
    if (selectedNotes.top.length === 0) {
        document.getElementById('topNotesError').textContent = 'Selecciona al menos una nota alta';
        hasErrors = true;
    }
    
    if (selectedNotes.heart.length === 0) {
        document.getElementById('heartNotesError').textContent = 'Selecciona al menos una nota de corazón';
        hasErrors = true;
    }
    
    if (selectedNotes.base.length === 0) {
        document.getElementById('baseNotesError').textContent = 'Selecciona al menos una nota de fondo';
        hasErrors = true;
    }
    
    if (hasErrors) return;
    
    // Enviar al servidor para validación
    try {
        const response = await fetch('/api/crear-perfume', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                tipo: 'notas',
                nombre,
                notasAltas: selectedNotes.top,
                notasCorazon: selectedNotes.heart,
                notasFondo: selectedNotes.base
            })
        });
        
        if (response.ok) {
            showNotification('¡Perfume creado exitosamente!');
            setTimeout(() => window.location.href = './src/carrito.html', 1500);
        } else {
            const data = await response.json();
            showNotification(data.error || 'Error al crear perfume');
        }
    } catch (error) {
        showNotification('Error al crear perfume');
    }
});

// Formulario simple
const simpleForm = document.getElementById('simpleForm');
const perfumeName2 = document.getElementById('perfumeName2');
const aromaType = document.getElementById('aromaType');
const previewName = document.getElementById('previewName');
const previewType = document.getElementById('previewType');

// Actualizar preview en tiempo real
perfumeName2.addEventListener('input', () => {
    previewName.textContent = perfumeName2.value || 'Tu Perfume';
});

aromaType.addEventListener('change', () => {
    const selectedOption = aromaType.options[aromaType.selectedIndex];
    previewType.textContent = selectedOption.text || 'Tipo de aroma';
});

simpleForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validación cliente
    const nombre = perfumeName2.value.trim();
    const tipo = aromaType.value;
    let hasErrors = false;
    
    // Limpiar errores
    document.getElementById('name2Error').textContent = '';
    document.getElementById('aromaError').textContent = '';
    
    if (nombre.length < 3) {
        document.getElementById('name2Error').textContent = 'El nombre debe tener al menos 3 caracteres';
        hasErrors = true;
    }
    
    if (!tipo) {
        document.getElementById('aromaError').textContent = 'Selecciona un tipo de aroma';
        hasErrors = true;
    }
    
    if (hasErrors) return;
    
    // Enviar al servidor para validación
    try {
        const response = await fetch('/api/crear-perfume', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                tipo: 'simple',
                nombre,
                tipoAroma: tipo
            })
        });
        
        if (response.ok) {
            showNotification('¡Perfume creado exitosamente!');
            setTimeout(() => window.location.href = '/src/carrito.html', 1500);
        } else {
            const data = await response.json();
            showNotification(data.error || 'Error al crear perfume');
        }
    } catch (error) {
        showNotification('Error al crear perfume');
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    renderNotes('top', document.getElementById('topNotes'));
    renderNotes('heart', document.getElementById('heartNotes'));
    renderNotes('base', document.getElementById('baseNotes'));
});
