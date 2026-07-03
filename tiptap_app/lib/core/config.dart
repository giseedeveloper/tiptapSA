class AppConfig {
  /// Laravel API root. Override per market at build time:
  /// SA: --dart-define=API_BASE_URL=https://tiptapafrica.co.za/api
  /// TZ: --dart-define=API_BASE_URL=https://tiptapafrica.co.tz/api
  /// Local: --dart-define=API_BASE_URL=http://127.0.0.1:8000/api
  static const String baseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://tiptapafrica.co.za/api',
  );

  static const int statsPollIntervalSeconds = 30;
}
