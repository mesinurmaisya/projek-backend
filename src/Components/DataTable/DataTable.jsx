import React from 'react';
import './DataTable.css';

const DataTable = ({ title, data, columns, onEdit, onDelete }) => {
  return (
    <div className="data-table-container">
      <h2 className="table-title">{title}</h2>
      <div className="table-wrapper">
        <table className="data-table">
          <thead>
            <tr>
              {columns.map((column, index) => (
                <th key={index}>{column.header}</th>
              ))}
              {(onEdit || onDelete) && <th>Aksi</th>}
            </tr>
          </thead>
          <tbody>
            {data.length === 0 ? (
              <tr>
                <td colSpan={(onEdit || onDelete) ? columns.length + 1 : columns.length} className="no-data">
                  Tidak ada data
                </td>
              </tr>
            ) : (
              data.map((row, rowIndex) => (
                <tr key={rowIndex}>
                  {columns.map((column, colIndex) => (
                    <td key={colIndex}>
                      {column.render ? column.render(row[column.key]) : row[column.key]}
                    </td>
                  ))}
                  {(onEdit || onDelete) && (
                    <td className="action-buttons">
                      {onEdit && (
                        <button 
                          className="btn-edit"
                          onClick={() => onEdit(row)}
                        >
                          Edit
                        </button>
                      )}
                      {onDelete && (
                        <button 
                          className="btn-delete"
                          onClick={() => onDelete(row)}
                        >
                          Hapus
                        </button>
                      )}
                    </td>
                  )}
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default DataTable; 