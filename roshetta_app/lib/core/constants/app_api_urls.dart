class ApiUrls {
  // Root
  static const domain = "http://10.0.2.2:3030";
  static const _root = "$domain/roshetta/api";

  // Global Auth
  static const login = "$_root/users/login";
  static const register = "$_root/users/register";
  static const verifyEmailCode = "$_root/users/active_email";
  static const getDoctorSpecialists = "$_root/users/doctor_specialist";

  // Shared protected pages
  static const explainVideo = "$_root/users/view_video";
  static const myprofile = "$_root/users/profile";
}
