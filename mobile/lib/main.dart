import 'package:flutter/material.dart';
import 'package:arzonyegulik_mobile/app_theme.dart';
import 'package:arzonyegulik_mobile/screens/home_screen.dart';

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
      home: const HomeScreen(),
    );
  }
}
