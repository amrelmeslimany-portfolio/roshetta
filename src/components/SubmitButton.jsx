const SubmitButton = ({ width, height }) => {
  return (
    <>
      <button
        className={`foucs:outline-2 mt-6 rounded-full bg-roshetta px-${
          width || 40
        } py-${
          height || 3
        } text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600`}
        type="submit"
      >
        تعديل البيانات
      </button>
    </>
  );
};
export default SubmitButton;
