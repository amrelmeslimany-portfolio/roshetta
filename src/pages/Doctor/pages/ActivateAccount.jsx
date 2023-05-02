import React, { useState } from 'react';
import { AppWrapper } from '../../../wrapper';
import { MdVerified, MdOutlineDisabledByDefault } from 'react-icons/md';
const ActivateAccount = () => {
  const [activated, setActivated] = useState(false);
  return (
    <>
      <div className="flex h-screen flex-col items-center justify-center ">
        {activated ? (
          <MdVerified className="mb-5 text-9xl text-roshetta" />
        ) : (
          <MdOutlineDisabledByDefault className="mb-5 text-9xl text-roshetta" />
        )}
        <h2 className="text-4xl text-slate-600">
          {activated ? 'حسابك منشط بالفعل' : 'حسابك غير منشط'}
        </h2>
        <button
          onClick={() => buttonMode('edit-info')}
          className=" foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300 active:bg-green-600  disabled:bg-slate-300"
          type="button"
          disabled={activated ? true : false}
        >
          طلب تنشيط
        </button>
      </div>
    </>
  );
};

export default AppWrapper(ActivateAccount);
