export const restaurantOrder = (currentOrder, selectedTable) => {
    return{
        table: (selectedTable?.prefix || '') + (selectedTable?.number || ''),
        items: currentOrder.items.map(item => ({
            name: item.product.name,
            quantity: item.quantity,
        })),
        notes: currentOrder.notes ?? '',
    }
};