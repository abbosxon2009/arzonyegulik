class ProductModel {
  final String id;
  final String name;
  final String image;
  final double price;
  final String description;
  final int restaurantId;

  const ProductModel({
    required this.id,
    required this.name,
    required this.image,
    required this.price,
    this.description = '',
    this.restaurantId = 0,
  });

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    return ProductModel(
      id: '${json['id'] ?? ''}',
      name: json['name'] as String? ?? '',
      image: json['image_url'] as String? ?? '',
      price: (json['price'] as num?)?.toDouble() ?? 0,
      description: json['description'] as String? ?? '',
      restaurantId: json['restaurant_id'] as int? ?? 0,
    );
  }
}
