import 'package:flutter/foundation.dart';
import '../models/product_model.dart';

class FavoritesProvider extends ChangeNotifier {
  final List<ProductModel> _favorites = [];

  List<ProductModel> get favorites => List.unmodifiable(_favorites);

  bool isFavorite(ProductModel product) => _favorites.any((item) => item.id == product.id);

  void toggleFavorite(ProductModel product) {
    if (isFavorite(product)) {
      _favorites.removeWhere((item) => item.id == product.id);
    } else {
      _favorites.add(product);
    }
    notifyListeners();
  }
}
