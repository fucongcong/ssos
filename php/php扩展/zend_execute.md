#### zend_execute_data 结构体

struct _zend_execute_data {
    struct _zend_op *opline;
    zend_function_state function_state;
    zend_op_array *op_array;
    zval *object;
    HashTable *symbol_table;
    struct _zend_execute_data *prev_execute_data;
    zval *old_error_reporting;
    zend_bool nested;
    zval **original_return_value;
    zend_class_entry *current_scope;
    zend_class_entry *current_called_scope;
    zval *current_this;
    struct _zend_op *fast_ret; /* used by FAST_CALL/FAST_RET (finally keyword) */
    zval *delayed_exception;
    call_slot *call_slots;
    call_slot *call;
};



#### zend_op_array 结构体

struct _zend_op_array {
    /* Common elements */
    zend_uchar type;
    const char *function_name;
    zend_class_entry *scope;
    zend_uint fn_flags;
    union _zend_function *prototype;
    zend_uint num_args;
    zend_uint required_num_args;
    zend_arg_info *arg_info;
    /* END of common elements */

    zend_uint *refcount;

    zend_op *opcodes;
    zend_uint last;

    zend_compiled_variable *vars;
    int last_var;

    zend_uint T;

    zend_uint nested_calls;
    zend_uint used_stack;

    zend_brk_cont_element *brk_cont_array;
    int last_brk_cont;

    zend_try_catch_element *try_catch_array;
    int last_try_catch;
    zend_bool has_finally_block;

    /* static variables support */
    HashTable *static_variables;

    zend_uint this_var;

    const char *filename;
    zend_uint line_start;
    zend_uint line_end;
    const char *doc_comment;
    zend_uint doc_comment_len;
    zend_uint early_binding; /* the linked list of delayed declarations */

    zend_literal *literals;
    int last_literal;

    void **run_time_cache;
    int  last_cache_slot;

    void *reserved[ZEND_MAX_RESERVED_RESOURCES];
};