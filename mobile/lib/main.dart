import 'package:flutter/material.dart';
import 'package:arzonyegulik_mobile/app_theme.dart';
import 'package:arzonyegulik_mobile/screens/home_screen.dart';
import 'package:arzonyegulik_mobile/screens/cart_screen.dart';
import 'package:arzonyegulik_mobile/screens/checkout_screen.dart';
import 'package:arzonyegulik_mobile/screens/profile_screen.dart';

void main() {
  runApp(const ArzonYegulikApp());
}

class ArzonYegulikApp extends StatelessWidget {
  const ArzonYegulikApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Arzon Yegulik',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: const MainNavigationScreen(),
    );
  }
}

class MainNavigationScreen extends StatefulWidget {
  const MainNavigationScreen({super.key});

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _selectedIndex = 0;

  final List<Widget> _screens = [
    const HomeScreen(),
    const CartScreen(),
    const CheckoutScreen(),
    const ProfileScreen(),
  ];

  void _onNavItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _screens[_selectedIndex],
      bottomNavigationBar: Container(
        padding: const EdgeInsets.fromLTRB(18, 10, 18, 14),
        decoration: const BoxDecoration(
          color: Colors.white,
          border: Border(top: BorderSide(color: AppTheme.border)),
        ),
        child: SafeArea(
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _BottomNavItem(
                icon: Icons.home_rounded,
                active: _selectedIndex == 0,
                onTap: () => _onNavItemTapped(0),
              ),
              _BottomNavItem(
                icon: Icons.favorite_border_rounded,
                active: _selectedIndex == 1,
                onTap: () => _onNavItemTapped(1),
              ),
              _BottomNavItem(
                icon: Icons.shopping_bag_outlined,
                active: _selectedIndex == 2,
                onTap: () => _onNavItemTapped(2),
              ),
              _BottomNavItem(
                icon: Icons.person_outline_rounded,
                active: _selectedIndex == 3,
                onTap: () => _onNavItemTapped(3),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _BottomNavItem extends StatelessWidget {
  final IconData icon;
  final bool active;
  final VoidCallback onTap;

  const _BottomNavItem({
    required this.icon,
    required this.active,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 48,
        height: 48,
        decoration: BoxDecoration(
          color: active ? AppTheme.primary.withOpacity(0.12) : Colors.transparent,
          borderRadius: BorderRadius.circular(14),
        ),
        child: Icon(
          icon,
          color: active ? AppTheme.primaryDark : AppTheme.textSecondary,
          size: 24,
        ),
      ),
    );
  }
}
