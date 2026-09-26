import 'package:flutter/foundation.dart';
import '../models/product_model.dart';

class CartItem {
  final ProductModel product;
  int quantity;

  CartItem({required this.product, this.quantity = 1});

  String get id => product.id;
  String get name => product.name;
  String get image => product.image;
  double get price => product.price;
}

class CartProvider extends ChangeNotifier {
  final List<CartItem> _items = [];

  List<CartItem> get items => List.unmodifiable(_items);
  double get subtotal => _items.fold(0, (sum, item) => sum + item.price * item.quantity);
  double get shippingCost => _items.isEmpty ? 0 : 8000;
  double get total => subtotal + shippingCost;

  void addItem(ProductModel product) {
    final index = _items.indexWhere((item) => item.id == product.id);
    if (index == -1) {
      _items.add(CartItem(product: product));
    } else {
      _items[index].quantity++;
    }
    notifyListeners();
  }

  void removeItem(String productId) {
    _items.removeWhere((item) => item.id == productId);
    notifyListeners();
  }

  void incrementQuantity(String productId) {
    final item = _find(productId);
    if (item != null) item.quantity++;
    notifyListeners();
  }

  void decrementQuantity(String productId) {
    final item = _find(productId);
    if (item == null) return;
    if (item.quantity > 1) {
      item.quantity--;
    } else {
      _items.remove(item);
    }
    notifyListeners();
  }

  void clear() {
    _items.clear();
    notifyListeners();
  }

  CartItem? _find(String productId) {
    for (final item in _items) {
      if (item.id == productId) return item;
    }
    return null;
  }
}
