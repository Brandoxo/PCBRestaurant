<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { computed } from 'vue';

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

const selectedCategory = ref('all');

const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') {
    return products.value.filter(product => product.is_active);
  }
  return products.value.filter(product => product.category.name === selectedCategory.value && product.is_active);
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
        <p class="text-center">Selecciona una categoría del Menú</p>

        <div v-if="showProducts" class="mt-4">
          <select v-model="selectedCategory" class="border p-2 rounded-lg w-full mb-4">
            <option disabled value="">Seleccionar categoría</option>
            <option value="all">Todas las categorías</option>
            <option v-for="category in [...new Set(products.map(p => p.category.name))]" :key="category" :value="category">
              {{ category }}
            </option>
          </select>

          <div v-if="filteredProducts.length > 0" class="text-center text-gray-500">
            <h4 class="text-xl font-semibold mb-2">Productos Disponibles:</h4>
            <ul class="list-disc list-inside max-h-64 overflow-y-auto grid grid-cols-4 gap-4">
              <li v-for="product in filteredProducts" :key="product.id" class="mb-1 flex flex-col">
                <div class="bg-white rounded shadow flex justify-between items-center flex-col">
                  <img :src="product.image" alt="Product Image" class="w-full h-32 object-cover mb-2" />
                  <span class="font-bold">{{ product.name }}</span><span class="font-extrabold text-black text-lg mb-">${{ product.price }}</span>
                  <button class="text-sm px-5 mb-2 py-2 uppercase font-extrabold bg-approveGreen/80 text-white hover:bg-approveGreen" @click="AddToOrder(product, selectedTable.number)">Agregar</button>
                </div>
              </li>
            </ul>
          </div>

        </div>

        <div class="flex justify-between mt-6">
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="closeOptions">Cerrar</button>
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="saveOrder">Guardar orden</button>
        </div>
      </div>
    </div>
</template>