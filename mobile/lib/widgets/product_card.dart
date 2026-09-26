import 'package:flutter/material.dart';
import '../models/product_model.dart';

class ProductCard extends StatelessWidget {
  final ProductModel product;
  final bool showFavoriteButton;
  final VoidCallback? onRemoveFavorite;

  const ProductCard({
    super.key,
    required this.product,
    this.showFavoriteButton = false,
    this.onRemoveFavorite,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            Container(
              width: 82,
              height: 82,
              decoration: BoxDecoration(
                color: const Color(0xFFE3F9EC),
                borderRadius: BorderRadius.circular(16),
              ),
              child: product.image.isEmpty
                  ? const Center(child: Text('🍽️', style: TextStyle(fontSize: 34)))
                  : ClipRRect(
                      borderRadius: BorderRadius.circular(16),
                      child: Image.network(
                        product.image,
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => const Center(child: Text('🍽️', style: TextStyle(fontSize: 30))),
                      ),
                    ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(product.name, style: const TextStyle(fontWeight: FontWeight.w800)),
                  const SizedBox(height: 6),
                  Text('${product.price.toStringAsFixed(0)} so\'m', style: const TextStyle(color: Colors.green, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
            if (showFavoriteButton)
              IconButton(
                onPressed: onRemoveFavorite,
                icon: const Icon(Icons.favorite_rounded, color: Colors.red),
              ),
          ],
        ),
      ),
    );
  }
}
