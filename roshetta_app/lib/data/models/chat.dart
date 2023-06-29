class MessageModel {
  late String? id;
  late String? name;
  late String? time;
  late String? message;
  late String? image;

  MessageModel({this.id, this.name, this.time, this.message, this.image});

  MessageModel.fromJson(Map<String, dynamic> item, {String? userImg}) {
    id = item["id"];
    name = item["name"].toString();
    time = item["time"];
    message = item["message"];
    image = item["name"] == "1" ? userImg : item["image"];
  }
}
