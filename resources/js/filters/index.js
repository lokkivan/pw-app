/**
 * @param data
 * @returns {string|*}
 */
export default function sortFormat(data) {
  const {prop, order} = data;
  if (order !== null) {
    return (order === 'ascending' ? '+' : '-') + prop
  } else {
    return '+id'
  }
}

