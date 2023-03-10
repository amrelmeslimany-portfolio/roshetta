class AppRoutes {
  // Auth
  static const String intro = "/intro";
  static const String login = "/login";
  static const String createAccount = "/create_account";
  static const String verifyEmailCode = "/verify_email_code";
  static const String forgotPassword = "/forgot_password";
// Pages
  static const String home = "/home";
  static const String myProfile = "/my_profile";
  static const String settings = "/settings";
  static const String appointments = "/appointments";
}

class AssetPaths {
  // Roots
  static const String _assetsPathIMGS = "assets/imgs";
  static const String _assetsPathVideos = "assets/videos";
  static const String _assetsPathLottie = "assets/lottie";
  static const String _assetsPathJSON = "assets/json";
  // Global
  static const String loading = '$_assetsPathLottie/loading.json';
  static const String empty = '$_assetsPathLottie/empty.json';
  static const String server = '$_assetsPathLottie/server.json';
  static const String offline = '$_assetsPathLottie/offline.json';
  static const String error = '$_assetsPathLottie/error.json';
  static const String appbar = '$_assetsPathIMGS/appbar-cover.jpg';
  static const String drawer = '$_assetsPathIMGS/drawer-cover.jpg';

  // Auth
  static const String introAuth = '$_assetsPathIMGS/intro_auth.svg';
  // Logo
  static const String logoIcon = '$_assetsPathIMGS/lg-icon.png';

  // Home
  static const String usageVideo = '$_assetsPathVideos/roshetta.mp4';

  // Files
  static const String governmentsJson = '$_assetsPathJSON/governments.json';
}
