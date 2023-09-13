<script setup>

import Welcome from "@/Components/Welcome.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ListVue from "@/Pages/ListVue.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Filtres
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ListVue />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
