import errors from '../../core/errors';

var addNamespace = (eventNames, namespace) => {
  if (!namespace) {
    throw errors.Error('E0017');
  }

  if (Array.isArray(eventNames)) {
    return eventNames.map(eventName => addNamespace(eventName, namespace)).join(' ');
  }

  if (eventNames.indexOf(' ') !== -1) {
    return addNamespace(eventNames.split(/\s+/g), namespace);
  }

  return "".concat(eventNames, ".").concat(namespace);
};

export default addNamespace;