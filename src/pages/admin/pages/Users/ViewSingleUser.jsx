import React, { useEffect, useState } from "react";
import { Link, Navigate, useNavigate, useParams } from "react-router-dom";
import { getUsers, viewUserDetails } from "../../API";
import images from "../../../../images";
import { GoVerified } from "react-icons/go";
import { TbEye, TbNurse } from "react-icons/tb";
import { BsReceiptCutoff } from "react-icons/bs";
import { SlOptions } from "react-icons/sl";
import {
  FaDemocrat,
  FaFileMedical,
  FaGasPump,
  FaLocationArrow,
  FaMailBulk,
  FaPhone,
  FaPrescription,
} from "react-icons/fa";
import { MyLoader } from "../../../../components";
const Staff = ({ staff, type }) => {
  const navigate = useNavigate();

  useEffect(() => {}, []);
  const handleClick = (name, type) => {
    getUsers(type, name).then((res) => {
      console.log(res.Data[0]);
      // return <Navigate to={`/admin/users/view/${type}/${res.Data[0].id}`} />;
      navigate(`/admin/users/view/${type}/${res.Data[0].id}`);
    });
  };
  if (staff?.length > 0) {
    return staff?.map((s, i) => {
      return (
        <div key={i} className="my-4">
          <div className="flex w-72 flex-col items-center justify-center gap-5 rounded-lg bg-gray-300 px-2 py-3">
            <a onClick={() => handleClick(s.name, s.type)} className="self-end">
              <TbEye className="mx-1 cursor-pointer text-4xl text-white" />
              <SlOptions />
            </a>
            <div className="h-20 w-20">
              <img
                // src={user.image}
                src={s.image}
                className="mx-auto block w-full rounded-full"
                alt="img-info"
              />
            </div>
            <h4 className="text-2xl font-bold text-gray-700">{s.name}</h4>
            <p>{s.age}</p>
          </div>
        </div>
      );
    });
  } else {
    return (
      <div className="my-4">
        <div className="flex w-72 flex-col items-center justify-center gap-5 rounded-lg bg-gray-300 px-2 py-3">
          <a
            onClick={() => handleClick(staff?.name, "pharmacist")}
            className="self-end"
          >
            <TbEye className="mx-1 cursor-pointer text-4xl text-white" />
            <SlOptions />
          </a>
          <div className="h-20 w-20">
            <img
              // src={user.image}
              src={staff?.image}
              className="mx-auto block w-full rounded-full"
              alt="img-info"
            />
          </div>
          <h4 className="text-2xl font-bold text-gray-700">{staff?.name}</h4>
          <p>{staff?.age}</p>
        </div>
      </div>
    );
  }
};

const AnalyticsCard = ({ logo, title, number }) => {
  return (
    <div className="my-4">
      <div className="flex items-center justify-between gap-4 rounded-lg bg-gray-300 px-5 py-3">
        <div className="flex flex-col-reverse items-start justify-center">
          <h4 className="text-sm text-gray-800">{title}</h4>
          <p className="text-3xl font-extrabold text-gray-600">{number}</p>
        </div>

        <div className="text-7xl text-white">{logo}</div>
      </div>
    </div>
  );
};

const ViewPharma = ({ user, type, id }) => {
  return (
    <>
      <div className="my-4 flex w-[80vw] flex-col items-start justify-center bg-gray-100 px-10">
        <img
          // src={user.image}
          src={user.logo}
          className="mx-auto block w-1/6 rounded-full"
          alt="img-info"
        />
        <div className="mx-auto flex items-center justify-center">
          <h2 className="text-2xl text-roshetta ">{user.name}</h2>
          <GoVerified className="mx-2 text-blue-600" />
        </div>
        <div className="mx-auto mb-9 text-center">
          <h2 className="m-auto text-xl text-slate-400">{user.ssd}</h2>
          <div className="">{user.ser_id}</div>
        </div>
        <div className="flex w-[70%] flex-wrap items-center justify-between gap-2">
          <div className=" flex w-72 items-center justify-between">
            <p>المالك:</p>
            <div className="w-3/4 rounded-lg bg-gray-200 p-4 font-bold">
              {user.owner}
            </div>
          </div>
          <div className=" flex w-72 items-center justify-between">
            <p>رقم الموبايل:</p>
            <div className="w-3/4 rounded-lg bg-gray-200 p-4 font-bold">
              {user.phone_number}
            </div>
          </div>
        </div>
        <p className="my-3">مواعيد العمل بالساعة:</p>
        <div className="flex w-[70%] flex-wrap items-center justify-between gap-2">
          <div className=" flex w-72 items-center justify-between">
            <p>تبدأ من:</p>
            <div className="w-3/4 rounded-lg bg-gray-200 p-4 font-bold">
              {user.start_working}
            </div>
          </div>
          <div className=" flex w-72 items-center justify-between">
            <p>تنتهي في:</p>
            <div className="w-3/4 rounded-lg bg-gray-200 p-4 font-bold">
              {user.end_working}
            </div>
          </div>
        </div>
        <div className="mt-6 flex w-[70%] flex-wrap items-center justify-between gap-2">
          <div className=" flex w-2/3 items-center justify-between">
            <p>العنوان:</p>
            <div className="w-72 rounded-lg bg-gray-200 p-4 font-bold">
              {user.governorate} / {user.address}
            </div>
          </div>
        </div>
        <h4 className="py-4 text-2xl font-bold text-slate-500">طاقم العمل</h4>
        <div className="flex items-center justify-center gap-5">
          <Staff type={type} id={id} staff={user.stuff} />
        </div>
        <h4 className="py-4 text-2xl font-bold text-slate-500">احصائية</h4>
        <div className="flex items-center justify-center gap-5">
          <AnalyticsCard
            logo={<TbNurse />}
            title={"عدد الروشتات"}
            number={user.number_of_prescript}
          />
          {user.type === "pharmacy" && (
            <AnalyticsCard
              logo={<BsReceiptCutoff />}
              title={"عدد الطلبات"}
              number={user.number_of_orders}
            />
          )}
        </div>
      </div>
    </>
  );
};
const ViewUser = ({ user }) => {
  return (
    <>
      <div className="my-4 w-[80vw] bg-gray-100">
        <img
          // src={user.image}
          src={user.image}
          className="mx-auto block w-1/6 rounded-full"
          alt="img-info"
        />
        <div className="mx-auto flex items-center justify-center">
          <h2 className="text-2xl text-roshetta ">{user.name}</h2>
          <GoVerified className="mx-2 text-blue-600" />
        </div>
        <div className="mx-auto text-center">
          <h2 className="m-auto text-xl text-slate-400 text-slate-500">
            {user.ssd}
          </h2>
        </div>

        <div className="m-auto mt-2 flex w-1/2 items-center justify-evenly gap-20 rounded-2xl bg-white py-2 shadow-lg ">
          <div className="flex flex-col items-center justify-center gap-2">
            <p className="text-slate-400">الجنس</p>
            <p>{user.gender}</p>
          </div>
          <div className="flex flex-col items-center justify-center">
            <p className="text-slate-400">تاريخ الميلاد</p>
            <p>{user.birth_date}</p>
          </div>
          <div className="flex flex-col items-center justify-center">
            <p className="text-slate-400">نوع الحساب</p>
            <p>{user.type}</p>
          </div>
        </div>
        <div className="mx-auto mt-4 flex w-1/2 flex-col items-start justify-start gap-3">
          <div className="flex items-start justify-start ">
            <FaFileMedical className="ml-8 text-xl text-slate-400" />{" "}
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">التخصص</p>
              <p className="font-bold text-slate-800">{user.specialist}</p>
            </div>
          </div>
          <div className="flex items-start justify-start">
            <FaMailBulk className="ml-8 text-xl text-slate-400" />{" "}
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">البريد الإلكتروني</p>
              <p className="font-bold text-slate-800">{user.email}</p>
            </div>
          </div>
          <div className="flex items-start justify-start">
            <FaPhone className="ml-8 text-xl text-slate-400" />{" "}
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">رقم التليفون</p>
              <p className="font-bold text-slate-800">{user.phone_number}</p>
            </div>
          </div>
          <div className="flex items-start justify-start">
            <FaLocationArrow className="ml-8 text-xl text-slate-400" />{" "}
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">العنوان</p>
              <p className="font-bold text-slate-800">{user.governorate}</p>
            </div>
          </div>
        </div>
        {/* <div className="mx-auto mt-4 flex w-1/2 items-center justify-around">
          <div className="flex flex-col items-start justify-start">
            <p className="text-slate-500">العيادات</p>
            <p className="font-bold text-slate-800">{user.number_clinic}</p>
          </div>
          <div className="flex flex-col items-start justify-start border-x-2 border-slate-300 px-20">
            <p className="text-slate-500">الروشتات</p>
            <p className="font-bold text-slate-800">
              {user.number_prescript}
            </p>
          </div>
          <div className="flex flex-col items-start justify-start">
            <p className="text-slate-500">المساعدين</p>
            <p className="font-bold text-slate-800">{user.number_appoint}</p>
          </div>
        </div> */}
        <div className="mx-auto flex items-center justify-center">
          {/* <button
          onClick={() => buttonMode('edit-info')}
          className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
          type="button"
        >
          تعديل الحساب
        </button> */}
        </div>
      </div>
    </>
  );
};

const ViewSingleUser = () => {
  const { type, id } = useParams();
  const [user, setUser] = useState({});
  const [loading, setLoading] = useState(false);
  useEffect(() => {
    setLoading(true);
    viewUserDetails(type, id).then((res) => {
      setUser(res.Data);
      setLoading(false);
      console.log(res.Data);
    });
  }, [type, id]);
  if (!user.name) {
    return (
      <>
        <MyLoader loading={loading} />
      </>
    );
  }
  if (user.name) {
    if (type === "pharmacy" || type === "clinic") {
      return <ViewPharma type={type} id={id} user={user} />;
    } else {
      return <ViewUser user={user} />;
    }
  }
};

export default ViewSingleUser;
