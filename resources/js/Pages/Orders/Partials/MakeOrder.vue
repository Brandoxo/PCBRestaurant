<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
  tables: Object,
  orders: Object,
  selectedTable: Object
});

const emit = defineEmits(['closeOptions']);
function closeOptions() {
  emit('closeOptions');
}


const products = ref([]);
const showProducts = ref(false);
const order = ref({
  mesa_id: props.selectedTable?.number ? props.selectedTable.number : null,
  items: []
});

// GetProducts
const GetProducts = async () => {
  try {
    const response = await axios.get(route('products.get'));
    products.value = response.data.data;
    showProducts.value = true;
    console.log('Products data fetched successfully:', products.value);
  } catch (error) {
    console.error('There was an error fetching the products data!', error);
  }
};

const saveOrder = () => {
  console.log('Save Order clicked');

};

const AddToOrder = (product, _selectedTable) => {
  if (!order.value.mesa_id) {
    order.value.mesa_id = _selectedTable;
  }
  const existingItem = order.value.items.find(item => item.product_id === product.id);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    order.value.items.push({
      product_id: product.id,
      quantity: 1,
      price: product.price
    });
  }
  console.log('=========================================');
  console.log('Current Order:', order.value);
  console.log('Add to Order clicked');
  console.log('Product added to order:', product);
  console.log('Current Order:', order.value);
};

onMounted(() => {
console.log('Selected Table Prop:', props.selectedTable);
  order.value.mesa_id = props.selectedTable?.number ? props.selectedTable.number : null;
  GetProducts();
  console.log('Component mounted.');

});

console.log(props)
</script>


<template>
    <div class="w-2/3 p-8" v-if="selectedTable">
      <div class="bg-gray-100 p-8 rounded-xl shadow-lg ">
        <h3 class="text-2xl font-bold mb-4">Orden para la Mesa #{{ selectedTable.number }}</h3>
        <p>Selecciona una opción...</p>

        <div v-if="showProducts" class="mt-4">
          <h4 class="text-xl font-semibold mb-2">Productos Disponibles:</h4>
          <ul class="list-disc list-inside max-h-64 overflow-y-auto">
            <li v-for="product in products" :key="product.id" class="mb-1 flex flex-col">
              <div>
                <strong>{{ product.name }}</strong> - ${{ product.price }}
                <button class="ml-4 px-2 py-1 bg-green-500 text-white rounded" @click="AddToOrder(product, selectedTable.number)">Agregar a la orden</button>
              </div>
              <div class="text-sm text-gray-500">
                Categoría: {{ product.category.name }}
              </div>
            </li>
          </ul>
        </div>

        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="closeOptions">Cerrar</button>
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="saveOrder">Guardar orden</button>
      </div>
    </div>
</template>