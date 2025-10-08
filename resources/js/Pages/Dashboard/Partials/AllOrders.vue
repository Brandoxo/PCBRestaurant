<script setup>
import Orders from "@/Components/Orders.vue";
import { onMounted, ref, watch } from "vue";
import { GridStack } from "gridstack";
import swal from "sweetalert2";
import "gridstack/dist/gridstack.min.css";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({ orders: Array });
const emit = defineEmits(["select"]);
const columns = ref(getColumns());
const gridRef = ref(null);
let grid;
const user = usePage().props.auth.user

const canHideOrders = computed(() => {
    return user && user.permissions && user.permissions.includes('hide orders')
})

const deletedOrderIds = ref(
    JSON.parse(localStorage.getItem("deletedOrderIds") || "[]")
);

function saveDeletedOrderIds() {
    localStorage.setItem(
        "deletedOrderIds",
        JSON.stringify(deletedOrderIds.value)
    );
}

function getColumns() {
    if (window.innerWidth < 1536) return 1;

    return 3;
}

function updateColumns() {
    if (grid) {
        grid.column(getColumns());
    }
}

onMounted(() => {
    columns.value = getColumns();
    if (gridRef.value) {
        grid = GridStack.init(
            {
                cellHeight: 180,
                column: columns.value,
                resizable: { handles: "none" },
                removable: true,
                removeTimeout: 100,
            },
            gridRef.value
        );

        grid.on("removed", function (event, items) {
            items.forEach((item) => {
                const orderId = item.el.getAttribute("data-gs-id");
                const order = props.orders.find(
                    (o) => o.id.toString() === orderId
                );
                if (
                    !deletedOrderIds.value.includes(orderId) &&
                    order.status === "Completada"
                ) {
                    deletedOrderIds.value.push(orderId);
                    swal.fire({
                        title: "Orden eliminada",
                        text: "La orden ha sido eliminada correctamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    });
                    saveDeletedOrderIds();
                } else if (order.status === 'Cancelada' && canHideOrders.value){
                    deletedOrderIds.value.push(orderId);
                    swal.fire({
                        title: "Orden Eliminada",
                        text: "Orden Cancelada eliminada exitosamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    });
                    saveDeletedOrderIds();
                } else if (order.status === 'En curso'){
                    swal.fire({
                        title: "Orden en curso...",
                        text: "No es posible eliminar las órdenes en curso.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    }).then(() => {
                        location.reload();
                    })
                }
                 else {
                    swal.fire({
                        title: "No se puede eliminar",
                        text: 'Tus permisos de usuario no permite eliminar Órdenes Canceladas. Solicite a un administrador.',
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });
        window.addEventListener("resize", updateColumns);
    }
});
</script>
<style scoped>
.grid-stack {
    width: 100%;
    gap: 0.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(0, 1fr));
}
.grid-stack-item {
    padding: 4px;
}

@media (max-width: 800px) {
    .grid-stack {
        grid-template-columns: repeat(1, minmax(0, 1fr));
        width: 80%;
        margin: auto;
    }
}

</style>

<template>
    <div ref="gridRef" class="grid-stack">
        <div
            v-if="!orders || orders.length === 0"
            v-for="n in 15"
            :key="n"
            class="animate-pulse bg-gray-300 2xl:w-56 h-28 rounded-lg grid-stack-item"
        ></div>
        <div
            v-for="(order, idx) in orders.filter(
                (o) => !deletedOrderIds.includes(o.id.toString())
            )"
            :key="order.id"
            @click="emit('select', order)"
            class="cursor-pointer overflow-y-auto hover:scale-[1.03] transition-all transform ease-in-out duration-300 grid-stack-item"
            :gs-x="idx % columns"
            :gs-y="Math.floor(idx / columns)"
            :gs-width="1"
            :gs-height="1"
            :data-gs-id="order.id"
        >
            <Orders :order="order" />
        </div>
    </div>
</template>
