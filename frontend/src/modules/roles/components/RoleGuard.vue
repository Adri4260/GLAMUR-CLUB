<script setup>
import { useRole } from '../composables/useRole'

// Ahora pedimos un permiso específico (ej: 'delete', 'edit')
const props = defineProps({
    requirePermission: {
        type: String,
        required: true
    }
})

const { can } = useRole()
</script>

<template>
    <slot v-if="can(requirePermission)"></slot>
</template>