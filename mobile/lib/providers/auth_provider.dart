import 'package:flutter/foundation.dart';
import '../models/user_model.dart';

class AuthProvider extends ChangeNotifier {
  UserModel? _currentUser = UserModel(
    id: 1,
    fullName: 'Aziza Karimova',
    email: 'customer@test.com',
    phone: '+998 90 123 45 67',
    createdAt: DateTime(2026, 1, 1),
    status: 'Faol',
  );

  UserModel? get currentUser => _currentUser;
  bool get isAuthenticated => _currentUser != null;

  void setUser(UserModel user) {
    _currentUser = user;
    notifyListeners();
  }

  void logout() {
    _currentUser = null;
    notifyListeners();
  }
}
