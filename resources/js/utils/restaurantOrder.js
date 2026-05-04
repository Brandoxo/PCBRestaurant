export const restaurantOrder = (currentOrder) => {
    return currentOrder.items.map(item => ({
        name: item.product.name,
        quantity: item.quantity,
    }));
};