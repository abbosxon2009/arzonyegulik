class UserModel {
  final int id;
  final String fullName;
  final String email;
  final String phone;
  final DateTime createdAt;
  final String? status;

  const UserModel({
    required this.id,
    required this.fullName,
    required this.email,
    required this.phone,
    required this.createdAt,
    this.status,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int? ?? 0,
      fullName: (json['name'] ?? json['full_name'] ?? '') as String,
      email: json['email'] as String? ?? '',
      phone: json['phone'] as String? ?? '',
      createdAt: DateTime.tryParse(json['created_at'] as String? ?? '') ?? DateTime.now(),
      status: json['is_active'] == false ? 'Nofaol' : 'Faol',
    );
  }
}
