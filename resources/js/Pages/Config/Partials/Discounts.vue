<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="font-semibold text-lg">Descuentos</h3>
        <p class="mb-6 text-sm text-gray-600">Configura los descuentos aplicables en el sistema.</p>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Descuento por Cliente Frecuente (%)</label>
            <input
                type="number"
                min="0"
                max="100"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model.number="frequentCustomerDiscount"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Descuento por Promoción Especial (%)</label>
            <input
                type="number"
                min="0"
                max="100"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model.number="specialPromotionDiscount"
            />
        </div>
    </div>
</template>