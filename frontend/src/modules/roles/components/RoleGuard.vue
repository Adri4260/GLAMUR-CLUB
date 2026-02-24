<script setup>
import { useRole } from '../composables/useRole'

// Recibimos por parámetro qué rol es necesario para ver el contenido
const props = defineProps({
    requireRole: {
        type: String,
        required: true
    }
})

const { hasRole } = useRole()
</script>

<template>
    <slot v-if="hasRole(requireRole)"></slot>
</template>