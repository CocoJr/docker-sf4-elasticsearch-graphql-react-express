/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";

export default gql`
  mutation adminUserUploadImgProfil($id: ID!, $file: Upload!) {
    adminUserUploadImgProfil(file: $file, id: $id) {
      file {
        id
        publicPath
      }
      errors {
        key
        message
      }
    }
  }
`;