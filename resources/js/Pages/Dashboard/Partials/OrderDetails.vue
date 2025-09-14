<script setup>
const props = defineProps({
    order: Object,
});

const foodCategories = [1, 2, 3, 4, 6, 7, 8, 20];
const drinkCategories = [5, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
console.log("order details:", props.order);
</script>

<template>
    <h2 class="text-lg font-semibold mt-2 mb- text-center text-white">
        Detalles de la orden
    </h2>
    <div
        class="bg-white p-6 rounded-lg shadow-md flex overflow-y-auto justify-between max-h-64 hover:scale-[1.02] transition-all transform ease-in-out duration-300"
    >
        <div class="flex flex-col gap-2">
            <template
                v-if="
                    order && order.order_details && order.order_details.length
                "
            >
                <span v-for="item in order.order_details" :key="item.id">
                    <img
                        v-if="
                            item.product &&
                            foodCategories.includes(item.product.category_id)
                        "
                        src="assets/icons/svg/details/utensils.svg"
                        alt="Food Icon"
                        class="inline w-5 ml-2 cursor-pointer"
                    />
                    <img
                        v-else-if="
                            item.product &&
                            drinkCategories.includes(item.product.category_id)
                        "
                        src="assets/icons/svg/details/drink.svg"
                        alt="Drink Icon"
                        class="inline w-5 ml-2 cursor-pointer"
                    />
                    {{ item.quantity }} x
                    {{ item.product ? item.product.name : "" }}
                </span>
            </template>
            <span v-else class="text-gray-400"
                >No hay detalles para esta orden.</span
            >
        </div>

        <div
            v-if="order && order.order_details && order.order_details.length"
            class="h-fit overflow-auto flex flex-col gap-2 items-end"
        >
            <div v-for="item in order.order_details" :key="item.id">
                <span
                    class="text-center"
                    v-if="item.product && item.product.category_id === 2"
                ></span>

                $ {{ item.unit_price }}
            </div>
        </div>
        <span v-else class="text-gray-400"
            >No hay detalles para esta orden.</span
        >
    </div>

    <div
        class="bg-white p-6 rounded-lg shadow-md flex h-fit justify-between hover:scale-[1.02] transition-all transform ease-in-out duration-300"
    >
        <h3 class="font-bold text-3xl">Total:</h3>
        <div class="flex items-center gap-4">
            <span class="font-bold text-2xl 2xl:text-3xl">
                $ {{ props.order?.total ? props.order.total : "0.00" }}</span
            >
            <button
                v-if="
                    props.order?.status === 'En curso' ? props.order.status : ''
                "
                class="bg-approveGreen hover:bg-green-900 transition-all transform duration-300 ease-in-out text-white px-4 py-2 rounded-lg"
            >
                Cobrar
            </button>
        </div>
    </div>
</template>
