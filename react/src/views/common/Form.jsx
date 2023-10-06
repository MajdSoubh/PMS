import React, { Component } from "react";
import _ from "lodash";
import Joi from "joi-browser";
import Input from "./Input";
import Select from "./Select";
import ImagesUploaders from "./ImagesUploaders";
import TextArea from "./TextArea";
import UploadProgress from "./UploadProgress";
class Form extends Component {
    state = {
        data: {},
        errors: {},
    };
    handleApiResponse = (response) => {
        if (response.data.message) {
            this.setState({
                message: {
                    type: response.data.status == "1" ? "success" : "danger",
                    body: response.data.message,
                },
            });
        }
        this.setState({ errors: {} });
        if (response.status == 422 && response.data.errors) {
            this.setState({ errors: response.data.errors });
        }
    };
    handleSubmit = (e) => {
        e.preventDefault();
        if (this.schema) {
            const errors = this.validation();
            if (errors) {
                return this.setState({ errors });
            }
        }
        this.doSubmit();
    };

    validation = () => {
        let errors = {};
        const options = { abortEarly: false };
        const validation = Joi.validate(this.state.data, this.schema, options);
        if (!validation.error) return null;
        for (let error of validation.error.details) {
            errors[error.path[0]] = error.message;
        }
        return errors;
    };

    validateProperty = ({ name, value }) => {
        const property = { [name]: value };
        const schema = { [name]: this.schema[name] };
        const { error } = Joi.validate(property, schema, { abortEarly: false });

        return error ? error.details[0].message : null;
    };
    handleFileChange = ({ target }) => {
        const { name, files: targetFiles } = target;
        const data = { ...this.state.data };
        const files = [...data[name]];
        _.forEach(targetFiles, (f) => {
            files.push(f);
        });
        data[name] = files;
        this.setState({ data });
    };
    handleFileUnSelect = (name, index) => {
        const data = { ...this.state.data };
        const files = [...data[name]];
        files.splice(index, 1);
        data[name] = files;
        this.setState({ data });
    };
    handleChange = async ({ target }, onChange) => {
        const data = _.cloneDeep(this.state.data);
        const errors = _.cloneDeep(this.state.errors);
        const { name } = target;
        if (target.type == "checkbox") {
            const { checked } = target;
            _.set(data, name, checked);
        } else {
            const { value } = target;
            _.set(data, name, value);
        }

        if (this.schema) {
            const errorMsg = this.validateProperty(target);

            errorMsg ? (errors[name] = errorMsg) : delete errors[name];
            this.setState({ errors });
        }
        await this.setState({ data });
        typeof onChange === "function" ? onChange(target) : null;
    };
    renderButton = (options) => {
        const { label, className, type, style, events } = options;
        return (
            <button {...events} style={style} type={type} className={className}>
                {label}
            </button>
        );
    };
    renderInput(options) {
        const {
            label = null,
            name,
            hint = null,
            type = "text",
            className = null,
            onChange = null,
            error = _.get(this.state.errors, name),
            events,
        } = options;
        return (
            <Input
                label={label}
                name={name}
                hint={hint}
                type={type}
                {...events}
                className={className}
                onChange={(target) => {
                    this.handleChange(target, onChange);
                }}
                value={_.get(this.state.data, name)}
                error={error}
            />
        );
    }

    renderImagesUpload(label, name, multiple = true, className = null) {
        return (
            <ImagesUploaders
                onChange={this.handleFileChange}
                onUnSelect={this.handleFileUnSelect}
                name={name}
                label={label}
                multiple={multiple}
                className={className}
                value={this.state.data[name]}
                error={this.state.errors[name]}
            />
        );
    }
    renderUploadProgress(progress) {
        return <UploadProgress progress={progress} />;
    }

    renderTextarea(options) {
        const {
            label,
            name,
            rows = 3,
            cols = 3,
            hint = null,
            className = null,
        } = options;
        return (
            <TextArea
                label={label}
                name={name}
                rows={rows}
                cols={cols}
                className={className}
                onChange={this.handleChange}
                value={_.get(this.state.data, name)}
                error={_.get(this.state.errors, name)}
            />
        );
    }
    renderSelect(options) {
        const {
            label = null,
            name,
            data = [],
            style = null,
            placeholder = "Select an option...",
            error = _.get(this.state.errors, name),
            ...extra
        } = options;
        const _value = data.find(
            (opt) => opt.value == _.get(this.state.data, name)
        );

        return (
            <Select
                name={name}
                label={label}
                options={data}
                onChange={this.handleChange}
                value={_value}
                placeholder={placeholder}
                error={error}
                style={style}
                {...extra}
            />
        );
    }
}

export default Form;
