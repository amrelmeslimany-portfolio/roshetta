import { Form, Input, Select } from "antd";
import React, { useEffect, useState } from "react";
import { USER_ROLES } from "../../../constants/data";
import { FaBalanceScale, FaRuler, FaTag } from "react-icons/fa";
import { Colors } from "../../../constants/colors";

const onGetGovernorates = async () => {
  const response = await fetch("/src/constants/goverments.json");
  return await response.json();
};

const AccountFields = () => {
  const [governorates, setGovernorates] = useState([]);
  const [accountRole, setAccountRole] = useState(null);

  const onRoleChange = (value) => setAccountRole(value);

  useEffect(() => {
    onGetGovernorates().then((result) => {
      const data = result.data.map((item) => {
        const temp = item["governorate_name_ar"];
        return { value: temp, label: temp };
      });
      setGovernorates(data);
    });
  }, []);

  return (
    <>
      <Form.Item
        name="governorate"
        rules={[
          {
            required: true,
            message: "يجب اختيار محافظة",
          },
        ]}
      >
        <Select
          className="br-round"
          placeholder="المحافظة"
          options={governorates}
        />
      </Form.Item>
      <Form.Item
        name="role"
        rules={[
          {
            required: true,
            message: "يجب اختيار نوع الحساب",
          },
        ]}
      >
        <Select
          onChange={onRoleChange}
          className="br-round"
          listHeight={500}
          placeholder="نوع الحساب"
          options={USER_ROLES}
        />
      </Form.Item>

      {/* NOTE open if role is doctor */}
      {accountRole === "patient" && (
        <>
          <Form.Item name="height">
            <Input
              placeholder="الطول"
              suffix={<FaRuler color={Colors.LIGHT_GRAY} />}
              className="br-round"
              addonBefore="سم"
            />
          </Form.Item>
          <Form.Item name="weight">
            <Input
              placeholder="الوزن"
              suffix={<FaBalanceScale color={Colors.LIGHT_GRAY} />}
              className="br-round"
              addonBefore="كجم"
            />
          </Form.Item>
        </>
      )}
      {accountRole === "doctor" && (
        <Form.Item name="specialist">
          <Input
            placeholder="التخصص الطبي"
            suffix={<FaTag color={Colors.LIGHT_GRAY} />}
            className="br-round"
          />
        </Form.Item>
      )}
    </>
  );
};

export default AccountFields;
